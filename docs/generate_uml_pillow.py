from PIL import Image, ImageDraw, ImageFont
import os

OUTPUT_DIR = os.path.dirname(os.path.abspath(__file__))

def get_font(size):
    try:
        return ImageFont.truetype("arial.ttf", size)
    except:
        return ImageFont.load_default()

def draw_rounded_rect(d, xy, radius, fill, outline=None):
    x1, y1, x2, y2 = xy
    d.rounded_rectangle(xy, radius=radius, fill=fill, outline=outline, width=2)

def generate_mcd():
    w, h = 1400, 900
    img = Image.new("RGB", (w, h), "white")
    d = ImageDraw.Draw(img)
    
    d.text((w//2-150, 20), "Modèle Conceptuel de Données (MCD)", fill="#1a1a2e", font=get_font(22))
    
    boxes = {
        'USER': (50, 100, 300, 320),
        'CHARACTER': (380, 100, 680, 400),
        'ACCESSORY': (750, 100, 1050, 300),
        'REVIEW': (380, 480, 680, 700),
        'CHAR_ACC': (750, 400, 1050, 550),
        'CONTACT': (1100, 100, 1350, 280),
    }
    
    texts = {
        'USER': "USER\n\nid PK\nemail UNIQUE\npseudo UNIQUE\npassword_hash\nrole\nstatus\ncreated_at",
        'CHARACTER': "CHARACTER\n\nid PK\nuser_id FK\nname UNIQUE\ngender\neye_shape\nnose_shape\nmouth_shape\nskin_color\nhair_color\neye_color\nstatus\nshared\ncreated_at",
        'ACCESSORY': "ACCESSORY\n\nid PK\nname\ntype\ndescription\nimage_url\nstatus",
        'REVIEW': "REVIEW\n\nid PK\ncharacter_id FK\nuser_id FK\nrating\ncomment\nstatus\ncreated_at",
        'CHAR_ACC': "CHARACTER_ACCESSORIES\n\ncharacter_id FK\naccessory_id FK",
        'CONTACT': "CONTACT_REQUEST\n\nid PK\nemail\npseudo\nmessage\nsent_at",
    }
    
    colors = {'USER': '#dbeafe', 'CHARACTER': '#dcfce7', 'ACCESSORY': '#fef3c7', 
              'REVIEW': '#fce7f3', 'CHAR_ACC': '#f3e8ff', 'CONTACT': '#ffedd5'}
    
    for key, box in boxes.items():
        draw_rounded_rect(d, box, 10, colors[key], "#333")
        d.text((box[0]+15, box[1]+15), texts[key], fill="#1a1a2e", font=get_font(13))
    
    # Relations
    relations = [
        ((300, 210), (380, 210), "1,N"),
        ((530, 400), (530, 480), "1,N"),
        ((300, 260), (380, 260), "1,N"),
        ((680, 250), (750, 250), "1,N"),
        ((680, 200), (750, 200), "1,N"),
    ]
    for (x1, y1), (x2, y2), label in relations:
        d.line([(x1, y1), (x2, y2)], fill="#333", width=2)
        mx, my = (x1+x2)//2, (y1+y2)//2
        d.rectangle([mx-15, my-10, mx+15, my+10], fill="white", outline="#333")
        d.text((mx-10, my-8), label, fill="#333", font=get_font(11))
    
    img.save(os.path.join(OUTPUT_DIR, "diagramme_mcd.png"))
    print("diagramme_mcd.png généré")

def generate_use_case():
    w, h = 1600, 1000
    img = Image.new("RGB", (w, h), "white")
    d = ImageDraw.Draw(img)
    
    d.text((w//2-200, 20), "Diagramme d'Utilisation (Use Case)", fill="#1a1a2e", font=get_font(22))
    
    # System boundary
    d.rectangle([300, 80, 1300, 950], outline="#1a1a2e", width=3)
    d.text((750, 90), "Système de Gestion de Personnages", fill="#1a1a2e", font=get_font(16))
    
    # Actors
    actors = {
        'Visiteur': (100, 250),
        'Utilisateur': (100, 500),
        'Employé': (100, 750),
        'Administrateur': (1500, 500),
    }
    
    for name, (x, y) in actors.items():
        d.ellipse([x-15, y-30, x+15, y], outline="#333", width=2)
        d.line([(x, y), (x, y+40)], fill="#333", width=2)
        d.line([(x-20, y+10), (x+20, y+10)], fill="#333", width=2)
        d.line([(x, y+10), (x-15, y+40)], fill="#333", width=2)
        d.line([(x, y+10), (x+15, y+40)], fill="#333", width=2)
        d.line([(x, y+40), (x-15, y+70)], fill="#333", width=2)
        d.line([(x, y+40), (x+15, y+70)], fill="#333", width=2)
        tw = d.textlength(name, font=get_font(13))
        d.text((x-tw//2, y+80), name, fill="#1a1a2e", font=get_font(13))
    
    # Use cases
    ucs = [
        (450, 180, "Consulter personnages"),
        (650, 180, "Contacter entreprise"),
        (850, 180, "Créer un compte"),
        (1050, 180, "Se connecter"),
        (450, 350, "Créer un personnage"),
        (650, 350, "Modifier personnage"),
        (850, 350, "Partager / Arrêter"),
        (1050, 350, "Déposer un avis"),
        (450, 520, "Valider personnages"),
        (650, 520, "Valider avis"),
        (850, 520, "Gérer accessoires"),
        (1050, 520, "Suspendre compte"),
        (650, 700, "Créer un employé"),
        (850, 700, "Consulter les logs"),
    ]
    
    for x, y, text in ucs:
        tw = d.textlength(text, font=get_font(12))
        draw_rounded_rect(d, [x-tw//2-10, y-15, x+tw//2+10, y+25], 20, '#fff3cd', '#333')
        d.text((x-tw//2, y-5), text, fill="#1a1a2e", font=get_font(12))
    
    # Links
    links = [
        ('Visiteur', 450, 180), ('Visiteur', 650, 180), ('Visiteur', 850, 180), ('Visiteur', 1050, 180),
        ('Utilisateur', 1050, 180), ('Utilisateur', 450, 350), ('Utilisateur', 650, 350),
        ('Utilisateur', 850, 350), ('Utilisateur', 1050, 350),
        ('Employé', 450, 520), ('Employé', 650, 520), ('Employé', 850, 520), ('Employé', 1050, 520),
        ('Administrateur', 450, 520), ('Administrateur', 650, 520), ('Administrateur', 850, 520),
        ('Administrateur', 1050, 520), ('Administrateur', 650, 700), ('Administrateur', 850, 700),
    ]
    
    for actor_name, ux, uy in links:
        ax, ay = actors[actor_name]
        d.line([(ax+20 if actor_name=='Administrateur' else ax-20, ay+35), (ux, uy)], fill="#666", width=1)
    
    img.save(os.path.join(OUTPUT_DIR, "diagramme_utilisation.png"))
    print("diagramme_utilisation.png généré")

def generate_sequence():
    w, h = 1400, 900
    img = Image.new("RGB", (w, h), "white")
    d = ImageDraw.Draw(img)
    
    d.text((w//2-200, 20), "Diagramme de Séquence - Création de personnage", fill="#1a1a2e", font=get_font(22))
    
    actors = ['Utilisateur', 'Front\n(Bootstrap/JS)', 'AuthController\n(PHP)', 'CharacterModel\n(PDO)', 'MySQL', 'LogModel\n(MongoDB)']
    x_positions = [100, 280, 500, 740, 980, 1220]
    
    # Lifelines
    for i, (name, x) in enumerate(zip(actors, x_positions)):
        d.rectangle([x-60, 80, x+60, 130], fill='#dbeafe', outline='#333', width=2)
        lines = name.split('\n')
        y_off = 95
        for line in lines:
            tw = d.textlength(line, font=get_font(12))
            d.text((x-tw//2, y_off), line, fill="#1a1a2e", font=get_font(12))
            y_off += 14
        d.line([(x, 130), (x, h-80)], fill="#ccc", width=1)
    
    # Messages
    messages = [
        (0, 1, 180, "1. Remplit formulaire"),
        (1, 2, 220, "2. POST + CSRF"),
        (2, 3, 260, "3. create(data)"),
        (3, 4, 300, "4. INSERT INTO characters"),
        (4, 3, 340, "5. OK (pending)"),
        (3, 2, 380, "6. Succès"),
        (2, 5, 420, "7. Enregistre log"),
        (2, 1, 460, "8. Redirect + flash"),
        (1, 0, 500, "9. Message succès"),
    ]
    
    for src, dst, y, label in messages:
        x1, x2 = x_positions[src], x_positions[dst]
        color = "#0d6efd" if src < dst else "#198754"
        d.line([(x1, y), (x2, y)], fill=color, width=2)
        # Arrow
        if x1 < x2:
            d.polygon([(x2, y), (x2-10, y-5), (x2-10, y+5)], fill=color)
        else:
            d.polygon([(x2, y), (x2+10, y-5), (x2+10, y+5)], fill=color)
        mid = (x1+x2)//2
        d.text((mid-80, y-18), label, fill="#333", font=get_font(11))
    
    # Activation bars
    for x in x_positions[1:4]:
        d.rectangle([x-5, 180, x+5, 520], fill="#e8f4f8", outline="#333", width=1)
    
    img.save(os.path.join(OUTPUT_DIR, "diagramme_sequence.png"))
    print("diagramme_sequence.png généré")

if __name__ == "__main__":
    generate_mcd()
    generate_use_case()
    generate_sequence()
    print("\nTous les diagrammes UML ont été générés avec Pillow !")
