from PIL import Image, ImageDraw, ImageFont
import os

OUTPUT_DIR = os.path.dirname(os.path.abspath(__file__))

def get_font(size):
    try:
        return ImageFont.truetype("arial.ttf", size)
    except:
        return ImageFont.load_default()

def draw_wireframe_desktop_home():
    w, h = 1200, 800
    img = Image.new("RGB", (w, h), "#f8f9fa")
    d = ImageDraw.Draw(img)
    
    # Navbar
    d.rectangle([0, 0, w, 60], fill="#1a1a2e")
    d.text((20, 18), "PixelVerse Studios", fill="white", font=get_font(22))
    nav_items = ["Accueil", "Personnages", "Contact", "Connexion"]
    x = 700
    for item in nav_items:
        d.text((x, 20), item, fill="white", font=get_font(14))
        x += 110
    
    # Hero
    d.rectangle([20, 80, w-20, 280], fill="#16213e")
    d.text((w//2-300, 140), "Bienvenue chez PixelVerse Studios", fill="white", font=get_font(28))
    d.text((w//2-250, 190), "Créateurs de mondes immersifs et d'expériences épiques", fill="#cccccc", font=get_font(16))
    
    # Cards row
    y = 310
    for i, title in enumerate(["Personnalisation avancée", "Visualisation en action", "Partage communautaire"]):
        x = 40 + i * 380
        d.rectangle([x, y, x+360, y+180], fill="white", outline="#dddddd")
        d.rectangle([x, y, x+360, y+40], fill="#e94560")
        d.text((x+10, y+10), title, fill="white", font=get_font(16))
        d.text((x+10, y+60), "Description de la fonctionnalité\net de ses avantages pour\nle joueur.", fill="#333333", font=get_font(13))
    
    # Footer
    d.rectangle([0, h-60, w, h], fill="#1a1a2e")
    d.text((w//2-180, h-40), "Mentions légales | CGV | © 2025 PixelVerse", fill="white", font=get_font(13))
    
    img.save(os.path.join(OUTPUT_DIR, "maquette_desktop_home.png"))
    print("maquette_desktop_home.png généré")

def draw_wireframe_desktop_login():
    w, h = 1200, 800
    img = Image.new("RGB", (w, h), "#f8f9fa")
    d = ImageDraw.Draw(img)
    
    # Navbar
    d.rectangle([0, 0, w, 60], fill="#1a1a2e")
    d.text((20, 18), "PixelVerse Studios", fill="white", font=get_font(22))
    
    # Form card
    cx, cy = w//2, h//2
    d.rectangle([cx-250, cy-200, cx+250, cy+200], fill="white", outline="#cccccc")
    d.text((cx-80, cy-170), "Connexion", fill="#1a1a2e", font=get_font(24))
    
    d.rectangle([cx-200, cy-100, cx+200, cy-60], outline="#cccccc")
    d.text((cx-190, cy-90), "Email", fill="#666666", font=get_font(14))
    
    d.rectangle([cx-200, cy-30, cx+200, cy+10], outline="#cccccc")
    d.text((cx-190, cy-20), "Mot de passe", fill="#666666", font=get_font(14))
    
    d.rectangle([cx-200, cy+60, cx+200, cy+110], fill="#0d6efd")
    d.text((cx-50, cy+75), "Se connecter", fill="white", font=get_font(16))
    
    d.text((cx-120, cy+140), "Mot de passe oublié ? | Créer un compte", fill="#0d6efd", font=get_font(13))
    
    img.save(os.path.join(OUTPUT_DIR, "maquette_desktop_login.png"))
    print("maquette_desktop_login.png généré")

def draw_wireframe_desktop_user():
    w, h = 1200, 800
    img = Image.new("RGB", (w, h), "#f8f9fa")
    d = ImageDraw.Draw(img)
    
    # Navbar
    d.rectangle([0, 0, w, 60], fill="#1a1a2e")
    d.text((20, 18), "PixelVerse Studios", fill="white", font=get_font(22))
    d.text((900, 20), "Mon Espace | Déconnexion", fill="white", font=get_font(14))
    
    # Sidebar
    d.rectangle([20, 80, 220, h-80], fill="white", outline="#dddddd")
    d.text((30, 100), "Menu", fill="#1a1a2e", font=get_font(18))
    for i, item in enumerate(["Mes personnages", "Créer", "Modifier", "Paramètres"]):
        d.text((30, 150 + i*40), item, fill="#555555", font=get_font(14))
    
    # Main content
    d.text((250, 100), "Mes Personnages", fill="#1a1a2e", font=get_font(22))
    d.rectangle([250, 150, 450, 180], fill="#0d6efd")
    d.text((270, 157), "+ Créer un personnage", fill="white", font=get_font(14))
    
    for i in range(3):
        x = 250 + i*310
        d.rectangle([x, 210, x+290, 400], fill="white", outline="#dddddd")
        d.rectangle([x, 210, x+290, 280], fill="#6c757d")
        d.text((x+120, 235), "🧙‍♂️", fill="white", font=get_font(30))
        d.text((x+20, 300), f"Personnage {i+1}", fill="#1a1a2e", font=get_font(16))
        d.text((x+20, 330), "Statut: Approuvé", fill="#198754", font=get_font(13))
        d.rectangle([x+20, 360, x+130, 390], outline="#0d6efd")
        d.text((x+30, 367), "Modifier", fill="#0d6efd", font=get_font(13))
        d.rectangle([x+150, 360, x+260, 390], outline="#dc3545")
        d.text((x+160, 367), "Supprimer", fill="#dc3545", font=get_font(13))
    
    img.save(os.path.join(OUTPUT_DIR, "maquette_desktop_user.png"))
    print("maquette_desktop_user.png généré")

def draw_wireframe_mobile_home():
    w, h = 400, 700
    img = Image.new("RGB", (w, h), "#f8f9fa")
    d = ImageDraw.Draw(img)
    
    # Navbar
    d.rectangle([0, 0, w, 50], fill="#1a1a2e")
    d.text((10, 15), "☰", fill="white", font=get_font(20))
    d.text((100, 15), "PixelVerse", fill="white", font=get_font(18))
    
    # Hero
    d.rectangle([10, 60, w-10, 200], fill="#16213e")
    d.text((30, 100), "Bienvenue", fill="white", font=get_font(20))
    d.text((30, 140), "FantasyRealm Online", fill="#cccccc", font=get_font(14))
    
    # Stacked cards
    y = 220
    for title in ["Personnalisation", "Visualisation", "Partage"]:
        d.rectangle([10, y, w-10, y+120], fill="white", outline="#dddddd")
        d.rectangle([10, y, w-10, y+30], fill="#e94560")
        d.text((20, y+7), title, fill="white", font=get_font(14))
        d.text((20, y+45), "Description courte de\ncette fonctionnalité.", fill="#333333", font=get_font(12))
        y += 140
    
    # Footer
    d.rectangle([0, h-40, w, h], fill="#1a1a2e")
    d.text((80, h-28), "Mentions légales | CGV", fill="white", font=get_font(11))
    
    img.save(os.path.join(OUTPUT_DIR, "maquette_mobile_home.png"))
    print("maquette_mobile_home.png généré")

def draw_wireframe_mobile_login():
    w, h = 400, 700
    img = Image.new("RGB", (w, h), "#f8f9fa")
    d = ImageDraw.Draw(img)
    
    # Navbar
    d.rectangle([0, 0, w, 50], fill="#1a1a2e")
    d.text((10, 15), "☰", fill="white", font=get_font(20))
    d.text((100, 15), "PixelVerse", fill="white", font=get_font(18))
    
    # Form
    d.text((120, 120), "Connexion", fill="#1a1a2e", font=get_font(22))
    d.rectangle([20, 180, w-20, 220], outline="#cccccc")
    d.text((30, 190), "Email", fill="#666666", font=get_font(14))
    d.rectangle([20, 240, w-20, 280], outline="#cccccc")
    d.text((30, 250), "Mot de passe", fill="#666666", font=get_font(14))
    d.rectangle([20, 320, w-20, 370], fill="#0d6efd")
    d.text((140, 337), "Se connecter", fill="white", font=get_font(16))
    d.text((60, 400), "Mot de passe oublié ?", fill="#0d6efd", font=get_font(13))
    d.text((60, 430), "Créer un compte", fill="#0d6efd", font=get_font(13))
    
    img.save(os.path.join(OUTPUT_DIR, "maquette_mobile_login.png"))
    print("maquette_mobile_login.png généré")

def draw_wireframe_mobile_user():
    w, h = 400, 700
    img = Image.new("RGB", (w, h), "#f8f9fa")
    d = ImageDraw.Draw(img)
    
    # Navbar
    d.rectangle([0, 0, w, 50], fill="#1a1a2e")
    d.text((10, 15), "☰", fill="white", font=get_font(20))
    d.text((100, 15), "Mon Espace", fill="white", font=get_font(18))
    
    d.text((20, 70), "Mes Personnages", fill="#1a1a2e", font=get_font(18))
    d.rectangle([20, 110, w-20, 145], fill="#0d6efd")
    d.text((80, 118), "+ Créer un personnage", fill="white", font=get_font(14))
    
    y = 170
    for i in range(3):
        d.rectangle([20, y, w-20, y+130], fill="white", outline="#dddddd")
        d.rectangle([20, y, 80, y+130], fill="#6c757d")
        d.text((35, y+45), "🧙‍♂️", fill="white", font=get_font(24))
        d.text((95, y+20), f"Perso {i+1}", fill="#1a1a2e", font=get_font(15))
        d.text((95, y+50), "Approuvé", fill="#198754", font=get_font(12))
        d.rectangle([95, y+85, 180, y+115], outline="#0d6efd")
        d.text((105, y+92), "Modifier", fill="#0d6efd", font=get_font(12))
        d.rectangle([200, y+85, 290, y+115], outline="#dc3545")
        d.text((205, y+92), "Supprimer", fill="#dc3545", font=get_font(12))
        y += 150
    
    img.save(os.path.join(OUTPUT_DIR, "maquette_mobile_user.png"))
    print("maquette_mobile_user.png généré")

if __name__ == "__main__":
    draw_wireframe_desktop_home()
    draw_wireframe_desktop_login()
    draw_wireframe_desktop_user()
    draw_wireframe_mobile_home()
    draw_wireframe_mobile_login()
    draw_wireframe_mobile_user()
    print("\nToutes les maquettes ont été générées !")
