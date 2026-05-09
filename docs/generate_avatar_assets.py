from PIL import Image, ImageDraw
import os

BASE_DIR = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))), 'public', 'assets', 'images', 'avatar')
W, H = 200, 280

def save(img, path):
    os.makedirs(os.path.dirname(path), exist_ok=True)
    img.save(path)
    print(f"Generated: {path}")

def new_canvas():
    return Image.new('RGBA', (W, H), (0, 0, 0, 0))

def ellipse(draw, box, fill, outline=None, width=2):
    draw.ellipse(box, fill=fill, outline=outline, width=width)

def rounded_rect(draw, box, radius, fill, outline=None, width=2):
    draw.rounded_rectangle(box, radius=radius, fill=fill, outline=outline, width=width)

# ============================================================
# CORPS (silhouettes selon corpulence)
# ============================================================
def gen_body():
    builds = {
        'maigre':   {'w': 60,  'h': 170, 'y': 70,  'r': 45},
        'musclé':   {'w': 110, 'h': 160, 'y': 75,  'r': 45},
        'gros':     {'w': 130, 'h': 150, 'y': 80,  'r': 55},
        'athlétique':{'w': 105, 'h': 158, 'y': 77,  'r': 45},
        'élancé':   {'w': 75,  'h': 175, 'y': 72,  'r': 45},
        'trapu':    {'w': 125, 'h': 155, 'y': 78,  'r': 50},
    }
    for name, cfg in builds.items():
        img = new_canvas()
        d = ImageDraw.Draw(img)
        x = (W - cfg['w']) // 2
        ellipse(d, [x, cfg['y'], x+cfg['w'], cfg['y']+cfg['h']], fill=(200,200,200,255), outline=(80,80,80,255), width=2)
        # tête
        hw = cfg['w'] * 0.5
        hh = hw
        hx = (W - hw) // 2
        hy = cfg['y'] - hh * 0.7
        ellipse(d, [hx, hy, hx+hw, hy+hh], fill=(200,200,200,255), outline=(80,80,80,255), width=2)
        save(img, os.path.join(BASE_DIR, 'body', f'{name}.png'))

# ============================================================
# PEAU (overlay coloré sur le corps)
# ============================================================
def gen_skin():
    colors = {
        'claire': (245, 208, 169, 230),
        'méditerranéenne': (212, 163, 115, 230),
        'foncée': (141, 85, 36, 230),
        'mate': (198, 134, 66, 230),
        'pâle': (253, 228, 208, 230),
        'rougeâtre': (230, 160, 141, 230),
    }
    for name, color in colors.items():
        img = new_canvas()
        d = ImageDraw.Draw(img)
        # corps
        ellipse(d, [45, 75, 155, 235], fill=color)
        # tête
        ellipse(d, [70, 35, 130, 95], fill=color)
        save(img, os.path.join(BASE_DIR, 'skin', f'{name}.png'))

# ============================================================
# CHEVEUX
# ============================================================
def gen_hair():
    colors = {
        'brun': (74, 55, 40, 255),
        'blond': (230, 194, 41, 255),
        'roux': (184, 84, 80, 255),
        'noir': (26, 26, 26, 255),
        'blanc': (240, 240, 240, 255),
        'gris': (156, 163, 175, 255),
        'châtain': (139, 90, 43, 255),
    }
    for name, color in colors.items():
        img = new_canvas()
        d = ImageDraw.Draw(img)
        # masse de cheveux
        ellipse(d, [55, 15, 145, 75], fill=color, outline=(40,40,40,255), width=2)
        # mèches
        ellipse(d, [60, 30, 90, 80], fill=color)
        ellipse(d, [110, 30, 140, 80], fill=color)
        save(img, os.path.join(BASE_DIR, 'hair', f'{name}.png'))

# ============================================================
# VÊTEMENTS (selon type de personnage)
# ============================================================
def gen_clothes():
    colors = {
        'guerrier': (153, 27, 27, 255),
        'mage': (30, 64, 175, 255),
        'archer': (6, 95, 70, 255),
        'voleur': (55, 65, 81, 255),
        'barbare': (120, 53, 15, 255),
        'sorcier': (88, 28, 135, 255),
        'paladin': (217, 119, 6, 255),
        'druide': (22, 101, 52, 255),
        'nécromancien': (17, 24, 39, 255),
    }
    for name, color in colors.items():
        img = new_canvas()
        d = ImageDraw.Draw(img)
        # tunique / robe
        rounded_rect(d, [55, 110, 145, 240], 20, fill=color, outline=(30,30,30,255), width=2)
        # col
        ellipse(d, [75, 95, 125, 125], fill=color, outline=(30,30,30,255), width=2)
        # ceinture
        rounded_rect(d, [55, 180, 145, 195], 5, fill=(80,50,20,255), outline=(30,30,30,255), width=1)
        save(img, os.path.join(BASE_DIR, 'clothes', f'{name}.png'))

# ============================================================
# YEUX
# ============================================================
def gen_eyes():
    colors = {
        'bleu': (74, 144, 226, 255),
        'vert': (74, 222, 128, 255),
        'marron': (120, 53, 15, 255),
        'noisette': (139, 105, 20, 255),
        'gris': (156, 163, 175, 255),
        'noir': (26, 26, 26, 255),
        'violet': (124, 58, 237, 255),
    }
    for name, color in colors.items():
        img = new_canvas()
        d = ImageDraw.Draw(img)
        # blancs
        ellipse(d, [70, 65, 95, 82], fill=(255,255,255,255), outline=(80,80,80,255), width=1)
        ellipse(d, [105, 65, 130, 82], fill=(255,255,255,255), outline=(80,80,80,255), width=1)
        # iris
        ellipse(d, [76, 68, 89, 79], fill=color, outline=(40,40,40,255), width=1)
        ellipse(d, [111, 68, 124, 79], fill=color, outline=(40,40,40,255), width=1)
        # pupille
        ellipse(d, [80, 71, 85, 76], fill=(0,0,0,255))
        ellipse(d, [115, 71, 120, 76], fill=(0,0,0,255))
        save(img, os.path.join(BASE_DIR, 'eyes', f'{name}.png'))

# ============================================================
# ARMURES
# ============================================================
def gen_armor():
    img = new_canvas()
    d = ImageDraw.Draw(img)
    # plastron métallique
    rounded_rect(d, [50, 108, 150, 200], 15, fill=(180,180,190,220), outline=(80,80,90,255), width=2)
    # épaulettes
    ellipse(d, [40, 105, 70, 135], fill=(160,160,170,220), outline=(80,80,90,255), width=2)
    ellipse(d, [130, 105, 160, 135], fill=(160,160,170,220), outline=(80,80,90,255), width=2)
    save(img, os.path.join(BASE_DIR, 'armor', 'heavy.png'))

    img = new_canvas()
    d = ImageDraw.Draw(img)
    # cuir léger
    rounded_rect(d, [60, 115, 140, 190], 10, fill=(139, 90, 43, 220), outline=(80,50,20,255), width=2)
    save(img, os.path.join(BASE_DIR, 'armor', 'light.png'))

# ============================================================
# ARMES
# ============================================================
def gen_weapons():
    # Épée
    img = new_canvas()
    d = ImageDraw.Draw(img)
    rounded_rect(d, [160, 80, 170, 200], 3, fill=(192,192,192,255), outline=(60,60,60,255), width=2) # lame
    rounded_rect(d, [150, 195, 180, 205], 3, fill=(80,50,20,255), outline=(40,40,40,255), width=2) # garde
    rounded_rect(d, [162, 205, 168, 230], 3, fill=(80,50,20,255), outline=(40,40,40,255), width=2) # poignée
    save(img, os.path.join(BASE_DIR, 'weapons', 'sword.png'))

    # Hache
    img = new_canvas()
    d = ImageDraw.Draw(img)
    rounded_rect(d, [160, 90, 170, 220], 3, fill=(80,50,20,255), outline=(40,40,40,255), width=2) # manche
    rounded_rect(d, [155, 85, 190, 110], 5, fill=(160,160,160,255), outline=(60,60,60,255), width=2) # lame
    save(img, os.path.join(BASE_DIR, 'weapons', 'axe.png'))

    # Arc
    img = new_canvas()
    d = ImageDraw.Draw(img)
    d.arc([150, 80, 190, 220], start=270, end=90, fill=(80,50,20,255), width=6)
    d.line([(170, 80), (170, 220)], fill=(192,192,192,255), width=2)
    save(img, os.path.join(BASE_DIR, 'weapons', 'bow.png'))

    # Dague
    img = new_canvas()
    d = ImageDraw.Draw(img)
    rounded_rect(d, [165, 110, 172, 180], 2, fill=(192,192,192,255), outline=(60,60,60,255), width=1)
    rounded_rect(d, [160, 178, 177, 185], 2, fill=(80,50,20,255), outline=(40,40,40,255), width=1)
    save(img, os.path.join(BASE_DIR, 'weapons', 'dagger.png'))

    # Bâton
    img = new_canvas()
    d = ImageDraw.Draw(img)
    rounded_rect(d, [162, 60, 168, 240], 2, fill=(80,50,20,255), outline=(40,40,40,255), width=2)
    ellipse(d, [155, 45, 175, 65], fill=(100,50,150,255), outline=(40,40,40,255), width=2)
    save(img, os.path.join(BASE_DIR, 'weapons', 'staff.png'))

if __name__ == '__main__':
    gen_body()
    gen_skin()
    gen_hair()
    gen_clothes()
    gen_eyes()
    gen_armor()
    gen_weapons()
    print("\nToutes les images avatar ont été générées !")
