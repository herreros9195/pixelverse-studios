from graphviz import Digraph
import os

OUTPUT_DIR = os.path.dirname(os.path.abspath(__file__))

def generate_mcd():
    dot = Digraph(comment='MCD PixelVerse', format='png')
    dot.attr(rankdir='TB', bgcolor='white', fontname='Arial')
    dot.attr('node', shape='box', style='rounded,filled', fillcolor='#e8f4f8', fontname='Arial')
    
    dot.node('USER', 'USER\n(id, email, pseudo,\npassword_hash, role,\nstatus, created_at)')
    dot.node('CHARACTER', 'CHARACTER\n(id, user_id, name,\ngender, eye_shape,\nnose_shape, mouth_shape,\nskin_color, hair_color,\neye_color, status,\nshared, created_at)')
    dot.node('ACCESSORY', 'ACCESSORY\n(id, name, type,\ndescription, image_url,\nstatus)')
    dot.node('REVIEW', 'REVIEW\n(id, character_id,\nuser_id, rating,\ncomment, status)')
    dot.node('CONTACT', 'CONTACT_REQUEST\n(id, email, pseudo,\nmessage, sent_at)')
    dot.node('CHAR_ACC', 'CHARACTER_\nACCESSORIES\n(character_id,\naccessory_id)')
    
    dot.edge('USER', 'CHARACTER', label='1,N', dir='none')
    dot.edge('USER', 'REVIEW', label='1,N', dir='none')
    dot.edge('CHARACTER', 'REVIEW', label='1,N', dir='none')
    dot.edge('CHARACTER', 'CHAR_ACC', label='1,N', dir='none')
    dot.edge('ACCESSORY', 'CHAR_ACC', label='1,N', dir='none')
    
    output_path = os.path.join(OUTPUT_DIR, 'diagramme_mcd')
    dot.render(output_path, cleanup=True)
    print(f"diagramme_mcd.png généré")

def generate_use_case():
    dot = Digraph(comment='Use Case PixelVerse', format='png')
    dot.attr(rankdir='LR', bgcolor='white', fontname='Arial')
    dot.attr('node', shape='ellipse', style='filled', fillcolor='#fff3cd', fontname='Arial')
    
    # Acteurs
    dot.node('VISITEUR', 'Visiteur', shape='actor', fillcolor='white')
    dot.node('USER', 'Utilisateur', shape='actor', fillcolor='white')
    dot.node('EMPLOYE', 'Employé', shape='actor', fillcolor='white')
    dot.node('ADMIN', 'Administrateur', shape='actor', fillcolor='white')
    
    # Use cases
    dot.node('UC1', 'Consulter les\npersonnages')
    dot.node('UC2', 'Contacter\nl\'entreprise')
    dot.node('UC3', 'Créer un compte')
    dot.node('UC4', 'Se connecter')
    dot.node('UC5', 'Créer un\npersonnage')
    dot.node('UC6', 'Modifier son\npersonnage')
    dot.node('UC7', 'Partager /\nArrêter partage')
    dot.node('UC8', 'Déposer un avis')
    dot.node('UC9', 'Valider les\npersonnages')
    dot.node('UC10', 'Valider les avis')
    dot.node('UC11', 'Gérer les\naccessoires')
    dot.node('UC12', 'Suspendre un\ncompte')
    dot.node('UC13', 'Créer un employé')
    dot.node('UC14', 'Consulter les logs')
    
    dot.edge('VISITEUR', 'UC1')
    dot.edge('VISITEUR', 'UC2')
    dot.edge('VISITEUR', 'UC3')
    dot.edge('VISITEUR', 'UC4')
    dot.edge('USER', 'UC4')
    dot.edge('USER', 'UC5')
    dot.edge('USER', 'UC6')
    dot.edge('USER', 'UC7')
    dot.edge('USER', 'UC8')
    dot.edge('EMPLOYE', 'UC9')
    dot.edge('EMPLOYE', 'UC10')
    dot.edge('EMPLOYE', 'UC11')
    dot.edge('EMPLOYE', 'UC12')
    dot.edge('ADMIN', 'UC13')
    dot.edge('ADMIN', 'UC14')
    dot.edge('ADMIN', 'UC9')
    dot.edge('ADMIN', 'UC10')
    dot.edge('ADMIN', 'UC11')
    dot.edge('ADMIN', 'UC12')
    
    output_path = os.path.join(OUTPUT_DIR, 'diagramme_utilisation')
    dot.render(output_path, cleanup=True)
    print(f"diagramme_utilisation.png généré")

def generate_sequence():
    dot = Digraph(comment='Sequence PixelVerse', format='png')
    dot.attr(rankdir='TB', bgcolor='white', fontname='Arial')
    
    # Acteurs en haut
    with dot.subgraph() as s:
        s.attr(rank='same')
        s.node('U', 'Utilisateur', shape='actor')
        s.node('F', 'Front\n(Bootstrap/JS)', shape='box')
        s.node('C', 'AuthController\n(PHP)', shape='box')
        s.node('M', 'CharacterModel\n(PDO)', shape='box')
        s.node('DB', 'MySQL', shape='cylinder')
        s.node('LOG', 'LogModel\n(MongoDB)', shape='cylinder', fillcolor='#e8f5e9')
    
    dot.edge('U', 'F', label='1. Remplit formulaire', dir='forward')
    dot.edge('F', 'C', label='2. POST + CSRF', dir='forward')
    dot.edge('C', 'M', label='3. create(data)', dir='forward')
    dot.edge('M', 'DB', label='4. INSERT', dir='forward')
    dot.edge('DB', 'M', label='5. OK (pending)', dir='forward')
    dot.edge('M', 'C', label='6. succès', dir='forward')
    dot.edge('C', 'LOG', label='7. log action', dir='forward')
    dot.edge('C', 'F', label='8. redirect + flash', dir='forward')
    dot.edge('F', 'U', label='9. Message succès', dir='forward')
    
    output_path = os.path.join(OUTPUT_DIR, 'diagramme_sequence')
    dot.render(output_path, cleanup=True)
    print(f"diagramme_sequence.png généré")

if __name__ == "__main__":
    generate_mcd()
    generate_use_case()
    generate_sequence()
    print("\nTous les diagrammes UML ont été générés !")
