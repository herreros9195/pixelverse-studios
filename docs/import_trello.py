#!/usr/bin/env python3
"""
Script d'import automatique d'un board Trello depuis un fichier JSON.

Usage :
    python import_trello.py

Pre-requis :
    1. Aller sur https://trello.com/app-key pour obtenir votre API Key
    2. Cliquer sur "Token" pour generer un token
    3. Remplacer les variables KEY et TOKEN ci-dessous
"""

import json
import requests
import sys

# ============================================================================
# CONFIGURATION : remplacez ces deux valeurs par les votres
# ============================================================================
KEY = "VOTRE_CLE_API_TRELLO"
TOKEN = "VOTRE_TOKEN_TRELLO"
# ============================================================================

JSON_FILE = "trello_board_data.json"
BASE_URL = "https://api.trello.com/1"


def api_post(endpoint, params=None, data=None):
    """Effectue un POST sur l'API Trello."""
    url = f"{BASE_URL}{endpoint}"
    default_params = {"key": KEY, "token": TOKEN}
    if params:
        default_params.update(params)
    resp = requests.post(url, params=default_params, json=data)
    if not resp.ok:
        print(f"ERREUR {resp.status_code} sur {endpoint}: {resp.text}")
        sys.exit(1)
    return resp.json()


def main():
    if KEY == "VOTRE_CLE_API_TRELLO" or TOKEN == "VOTRE_TOKEN_TRELLO":
        print("=" * 60)
        print("AVANT DE LANCER CE SCRIPT :")
        print("1. Allez sur https://trello.com/app-key")
        print("2. Copiez votre API Key")
        print("3. Cliquez sur 'Token' pour generer un token")
        print("4. Remplacez KEY et TOKEN dans ce script (lignes 20-21)")
        print("=" * 60)
        sys.exit(1)

    # Charger le JSON
    with open(JSON_FILE, "r", encoding="utf-8") as f:
        data = json.load(f)

    board_name = data["board_name"]
    board_desc = data.get("board_desc", "")
    lists_data = data["lists"]
    labels_data = data.get("labels", [])

    print(f"Creation du board : {board_name}")

    # 1. Creer le board
    board = api_post("/boards/", {"name": board_name, "desc": board_desc})
    board_id = board["id"]
    board_url = board["shortUrl"]
    print(f"  -> Board cree : {board_url}")

    # 2. Creer les labels
    label_map = {}  # nom -> id
    for lbl in labels_data:
        created = api_post(
            f"/boards/{board_id}/labels",
            {"name": lbl["name"], "color": lbl["color"]}
        )
        label_map[lbl["name"]] = created["id"]
        print(f"  -> Label cree : {lbl['name']} ({lbl['color']})")

    # 3. Creer les listes et cartes
    for lst in lists_data:
        list_name = lst["name"]
        created_list = api_post("/lists", {"name": list_name, "idBoard": board_id})
        list_id = created_list["id"]
        print(f"  -> Liste creee : {list_name}")

        for card in lst.get("cards", []):
            card_params = {
                "name": card["name"],
                "desc": card.get("desc", ""),
                "idList": list_id,
            }
            if card.get("due"):
                card_params["due"] = card["due"]

            created_card = api_post("/cards", card_params)

            # Associer les labels
            for lbl_name in card.get("labels", []):
                if lbl_name in label_map:
                    api_post(
                        f"/cards/{created_card['id']}/idLabels",
                        {"value": label_map[lbl_name]}
                    )

            print(f"      -> Carte creee : {card['name']}")

    print("\n" + "=" * 60)
    print("BOARD CREE AVEC SUCCES !")
    print(f"Lien public : {board_url}")
    print("=" * 60)
    print("\nN'oubliez pas de rendre le board PUBLIC pour le partager.")
    print("(Menu '...' du board > Settings > Visibility > Public)")


if __name__ == "__main__":
    main()
