function slugify(str) {
    if (!str) return '';
    return str.toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]/g, '');
}

function getAvatarPath(folder, value) {
    if (!value) return '';
    return '/assets/images/avatar/' + folder + '/' + slugify(value) + '.png?v=2';
}

function setLayerSrc(builderId, layerName, value, folder) {
    const builder = document.getElementById(builderId);
    if (!builder) return;
    const img = builder.querySelector('[data-layer="' + layerName + '"]');
    if (!img) return;
    const path = getAvatarPath(folder, value);
    if (path) {
        img.src = path;
        img.style.opacity = '1';
    } else {
        img.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
        img.style.opacity = '0';
    }
}

function setSkinColor(builderId, colorValue) {
    const builder = document.getElementById(builderId);
    if (!builder) return;
    const skin = builder.querySelector('.avatar-skin-overlay');
    if (!skin) return;
    if (colorValue && colorValue.startsWith('#')) {
        skin.style.backgroundColor = colorValue;
    } else {
        // Fallback si ancienne valeur texte
        skin.style.backgroundColor = '#f5d0a9';
    }
}

function setNose(builderId, noseName) {
    const builder = document.getElementById(builderId);
    if (!builder || !noseName) return;
    let nose = builder.querySelector('.avatar-nose');
    if (!nose) {
        nose = document.createElement('div');
        builder.appendChild(nose);
    }
    nose.style.display = 'block';
    nose.className = 'avatar-nose avatar-nose-' + slugify(noseName);
}

function setMouth(builderId, mouthName) {
    const builder = document.getElementById(builderId);
    if (!builder || !mouthName) return;
    let mouth = builder.querySelector('.avatar-mouth');
    if (!mouth) {
        mouth = document.createElement('div');
        builder.appendChild(mouth);
    }
    mouth.style.display = 'block';
    mouth.className = 'avatar-mouth avatar-mouth-' + slugify(mouthName);
}

function applyBuildScale(builderId, buildValue) {
    const builder = document.getElementById(builderId);
    if (!builder || !buildValue) return;
    const classes = builder.className.split(' ').filter(c => !c.startsWith('avatar-builder-scale-'));
    builder.className = classes.join(' ');
    builder.classList.add('avatar-builder-scale-' + slugify(buildValue));
}

// ========== LIVE PREVIEW ==========
function setupLivePreview() {
    const preview = document.getElementById('live-avatar-preview');
    if (!preview) return;

    const handlers = {
        'character_type': (val) => setLayerSrc('live-avatar-preview', 'clothes', val, 'clothes'),
        'build': (val) => { setLayerSrc('live-avatar-preview', 'body', val, 'body'); applyBuildScale('live-avatar-preview', val); },
        'skin_color': (val) => setSkinColor('live-avatar-preview', val),
        'hair_color': (val) => setLayerSrc('live-avatar-preview', 'hair', val, 'hair'),
        'eye_color': (val) => setLayerSrc('live-avatar-preview', 'eyes', val, 'eyes'),
        'nose_shape': (val) => setNose('live-avatar-preview', val),
        'mouth_shape': (val) => setMouth('live-avatar-preview', val)
    };

    // Initialisation au chargement
    document.querySelectorAll('select, input[type="color"]').forEach(input => {
        if (handlers[input.name] && input.value) {
            handlers[input.name](input.value);
        }
    });

    // Écoute des changements
    document.querySelectorAll('select, input[type="color"]').forEach(input => {
        input.addEventListener('change', function() {
            if (handlers[this.name]) {
                handlers[this.name](this.value);
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', setupLivePreview);
// Feature: avatar-builder branch active
