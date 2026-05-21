import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { FBXLoader } from 'three/addons/loaders/FBXLoader.js';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

const viewers = {};
let manifestCache = null;

function isElementVisible(element) {
  if (!element || !element.isConnected) return false;
  if (element.hidden) return false;
  if (element.closest('[hidden]')) return false;

  const style = window.getComputedStyle(element);
  if (style.display === 'none' || style.visibility === 'hidden') return false;

  return element.getClientRects().length > 0;
}

const SKIN_TONES = {
  claire: new THREE.Color(0xf5d0a9),
  mediterraneenne: new THREE.Color(0xd4a373),
  mate: new THREE.Color(0xc68642),
  foncee: new THREE.Color(0x8d5524),
  pale: new THREE.Color(0xfde4d0),
  rougeatre: new THREE.Color(0xe6a08d)
};

const HAIR_COLORS = {
  brun: new THREE.Color(0x4b2e20),
  blond: new THREE.Color(0xd9b65b),
  roux: new THREE.Color(0xb45309),
  noir: new THREE.Color(0x171717),
  blanc: new THREE.Color(0xe5e7eb),
  gris: new THREE.Color(0x8a8a8a),
  chatain: new THREE.Color(0x7a5533)
};

const EYE_COLORS = {
  marron: new THREE.Color(0x6b4423),
  bleu: new THREE.Color(0x2563eb),
  vert: new THREE.Color(0x15803d),
  gris: new THREE.Color(0x64748b),
  noisette: new THREE.Color(0xd97706),
  noir: new THREE.Color(0x1f1f1f),
  violet: new THREE.Color(0x7c3aed)
};

const CLASS_ACCENTS = {
  guerrier: 0x335c9f,
  sentinelle: 0x37b7ff,
  ronin: 0xef6b6b,
  occultiste: 0x8b5cf6
};

const BODY_CATEGORY_ORDER = ['torso', 'upperarms', 'forearms', 'hands', 'hips', 'legs', 'feet'];

const BODY_STYLE_MODELS = {
  body_01: { id: 'body_01', species: 'humanspecies_01', path: 'synty/species/humanspecies_01.glb' },
  body_02: { id: 'body_02', species: 'female_muscular_02', path: 'synty/presets/female_muscular_02.glb' },
  body_03: { id: 'body_03', species: 'humanspecies_03', path: 'synty/species/humanspecies_03.glb' },
  body_04: { id: 'body_04', species: 'humanspecies_04', path: 'synty/species/humanspecies_04.glb' }
};

const HEADLESS_APPEARANCE_BODIES = {
  body_02: { path: 'synty/presets/female_body_02.glb', bust: 'female', scale: 1.12, offsetY: 0.28 },
  body_04: { path: 'synty/presets/male_body_04.glb', bust: 'male', scale: 1.12, offsetY: 0.28 }
};

const BODY_STYLE_APPEARANCE_DEFAULTS = {
  body_02: { head: 1, face: 1, ear: 1, brow: 1, nose: 1, hair: 10 },
  body_04: { head: 1, face: 1, ear: 1, brow: 3, nose: 6, hair: 2 }
};

const BODY_FAMILY_PREFIX = {
  human: 'sk_humn_base_01_',
  fantasy: 'sk_fant_kngt_17_',
  scifi: 'sk_scfi_civl_09_'
};

const CLASS_PRESETS = {
  guerrier: { defaultVariant: 'warrior_full', bodyFamily: 'fantasy' },
  sentinelle: { defaultVariant: 'sentinel_full', bodyFamily: 'scifi' },
  ronin: { defaultVariant: 'ronin_fox', bodyFamily: 'scifi' },
  occultiste: { defaultVariant: 'occult_hood', bodyFamily: 'scifi' }
};

const OUTFIT_VARIANTS = {
  warrior_core: {
    family: 'guerrier',
    bodyFamily: 'fantasy',
    accessoryPrefixes: [],
    faceAttachmentId: null
  },
  warrior_full: {
    family: 'guerrier',
    bodyFamily: 'fantasy',
    accessoryPrefixes: ['sk_fant_kngt_17_'],
    faceAttachmentId: 'sk_fant_kngt_17_23afac_hu01'
  },
  sentinel_core: {
    family: 'sentinelle',
    bodyFamily: 'scifi',
    accessoryPrefixes: [],
    faceAttachmentId: null
  },
  sentinel_full: {
    family: 'sentinelle',
    bodyFamily: 'scifi',
    accessoryPrefixes: ['sk_scfi_civl_09_'],
    faceAttachmentId: 'sk_scfi_civl_09_23afac_hu01'
  },
  ronin_fox: {
    family: 'ronin',
    bodyFamily: 'scifi',
    accessoryPrefixes: ['sk_scfi_civl_10_'],
    faceAttachmentId: null
  },
  occult_hood: {
    family: 'occultiste',
    bodyFamily: 'scifi',
    accessoryIds: ['sk_horr_viln_01_22ahed_hu01'],
    faceAttachmentId: null
  }
};

const LEGACY_STYLE_MAP = {
  brow: {
    male: {
      amande: 1,
      rond: 2,
      enamande: 3,
      bride: 4,
      enfonce: 5,
      proeminent: 3
    },
    female: {
      amande: 6,
      rond: 7,
      enamande: 8,
      bride: 9,
      enfonce: 10,
      proeminent: 8
    }
  },
  face: {
    male: {
      fine: 1,
      moyenne: 2,
      large: 3,
      encoeur: 4
    },
    female: {
      fine: 6,
      moyenne: 7,
      large: 8,
      encoeur: 9
    }
  },
  hair: {
    male: {
      court: 1,
      degrade: 2,
      milong: 3,
      long: 4,
      attache: 5,
      volumineux: 5
    },
    female: {
      court: 6,
      degrade: 6,
      milong: 7,
      long: 8,
      attache: 9,
      volumineux: 10
    }
  },
  nose: {
    male: {
      droit: 1,
      aquilin: 2,
      camus: 3,
      rond: 4,
      plat: 5,
      retrousse: 6
    },
    female: {
      droit: 1,
      aquilin: 2,
      camus: 3,
      rond: 4,
      plat: 5,
      retrousse: 6
    }
  }
};

function getAssetsBase() {
  return typeof window.ASSETS_URL !== 'undefined' ? window.ASSETS_URL : '/assets/';
}

function slugify(value) {
  if (!value) return '';

  return String(value)
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9]/g, '');
}

function pickGender(value) {
  return slugify(value) === 'female' || slugify(value) === 'feminin' ? 'female' : 'male';
}

function optionBool(value, defaultValue = true) {
  if (value === undefined || value === null || value === '') return defaultValue;
  if (value === true || value === false) return value;

  const normalized = String(value).toLowerCase().trim();
  if (['false', '0', 'no', 'non', 'off'].includes(normalized)) return false;
  if (['true', '1', 'yes', 'oui', 'on'].includes(normalized)) return true;
  return defaultValue;
}

function defaultStyleNumbers(gender, bodyStyle = '') {
  const normalizedBodyStyle = normalizeBodyStyle(bodyStyle, gender);
  const profile = BODY_STYLE_APPEARANCE_DEFAULTS[normalizedBodyStyle];
  if (profile) {
    return profile;
  }

  return pickGender(gender) === 'female'
    ? { face: 1, ear: 1, brow: 1, nose: 1, hair: 10 }
    : { face: 1, ear: 1, brow: 3, nose: 6, hair: 2 };
}

function clampStyleNumber(kind, value, gender) {
  const isFemale = pickGender(gender) === 'female';

  if (kind === 'face' || kind === 'ear' || kind === 'brow' || kind === 'hair') {
    const min = isFemale ? 6 : 1;
    const max = isFemale ? 10 : 5;
    return THREE.MathUtils.clamp(value, min, max);
  }

  if (kind === 'nose') {
    return THREE.MathUtils.clamp(value, 1, 11);
  }

  return value;
}

function parseIndexedChoice(kind, value, gender, bodyStyle = '') {
  const defaults = defaultStyleNumbers(gender, bodyStyle);
  const normalized = slugify(value || '');

  const match = normalized.match(new RegExp('^' + kind + '(\\d{1,2})$'));
  if (match) {
    return clampStyleNumber(kind, parseInt(match[1], 10), gender);
  }

  const legacyMap = LEGACY_STYLE_MAP[kind];
  const genderKey = pickGender(gender) === 'female' ? 'female' : 'male';
  if (legacyMap && legacyMap[genderKey] && legacyMap[genderKey][normalized]) {
    return clampStyleNumber(kind, legacyMap[genderKey][normalized], gender);
  }

  return defaults[kind];
}

function isFaceOverlayDisabled(value) {
  const normalized = slugify(value || '');
  return !normalized || ['facenone', 'none', 'aucun', 'neutre', 'neutral', 'sansbarbe', 'sansmasque'].includes(normalized);
}

function normalizeCharacterType(value) {
  const slug = slugify(value || 'guerrier');

  if (['guerrier', 'paladin', 'barbare'].includes(slug)) return 'guerrier';
  if (['sentinelle', 'mage'].includes(slug)) return 'sentinelle';
  if (['ronin', 'archer', 'voleur'].includes(slug)) return 'ronin';
  if (['occultiste', 'sorcier', 'druide', 'necromancien'].includes(slug)) return 'occultiste';
  return 'guerrier';
}

function normalizeOutfitVariant(characterType, outfitVariant) {
  const classKey = normalizeCharacterType(characterType);
  const normalizedVariant = String(outfitVariant || '').toLowerCase().trim();
  const variant = OUTFIT_VARIANTS[normalizedVariant];

  if (variant && variant.family === classKey) {
    return normalizedVariant;
  }

  return CLASS_PRESETS[classKey]?.defaultVariant || 'warrior_full';
}

function normalizeBodyStyle(bodyStyle, gender = 'male') {
  const normalized = slugify(bodyStyle || '');
  const pickedGender = pickGender(gender);

  for (const [key, definition] of Object.entries(BODY_STYLE_MODELS)) {
    const allowedGenders = key === 'body_02' ? ['female'] : ['male'];
    if ((slugify(key) === normalized || slugify(definition.species || '') === normalized)
      && allowedGenders.includes(pickedGender)) {
      return key;
    }
  }

  return pickedGender === 'female' ? 'body_02' : 'body_04';
}

function resolveBodyStyleModel(bodyStyle, gender = 'male') {
  return BODY_STYLE_MODELS[normalizeBodyStyle(bodyStyle, gender)] || BODY_STYLE_MODELS.body_04;
}

function resolveHeadlessAppearanceBody(bodyStyle, gender = 'male') {
  const normalized = normalizeBodyStyle(bodyStyle, gender);
  return HEADLESS_APPEARANCE_BODIES[normalized] || null;
}

function padded(number) {
  return String(number).padStart(2, '0');
}

function getPartList(manifest, category) {
  return Array.isArray(manifest?.parts?.[category]) ? manifest.parts[category] : [];
}

function findEntriesByPrefix(manifest, category, prefix) {
  const loweredPrefix = String(prefix || '').toLowerCase();
  return getPartList(manifest, category).filter((entry) => String(entry.id || '').toLowerCase().startsWith(loweredPrefix));
}

function findEntryById(manifest, category, id) {
  return getPartList(manifest, category).find((entry) => String(entry.id || '').toLowerCase() === String(id || '').toLowerCase()) || null;
}

function findHumanEntryByStyle(manifest, category, styleNumber) {
  const prefix = 'sk_humn_base_' + padded(styleNumber) + '_';
  return findEntriesByPrefix(manifest, category, prefix)[0] || null;
}

function findHumanPairByStyle(manifest, category, styleNumber) {
  const prefix = 'sk_humn_base_' + padded(styleNumber) + '_';
  return findEntriesByPrefix(manifest, category, prefix);
}

function resolveModularPath(part) {
  const path = typeof part === 'string' ? part : part?.url || '';
  if (!path) return '';

  if (/^synty\/modular\//i.test(path)) {
    return path.replace(/\.(glb|gltf)$/i, '.fbx');
  }

  return path;
}

function getAssetUrl(path) {
  const base = getAssetsBase();
  return base + 'models/' + String(path || '').replace(/^\/?assets\/models\//, '');
}

function resolveAssetUrl(pathOrUrl) {
  const value = String(pathOrUrl || '').trim();
  if (!value) {
    return '';
  }

  if (/^(https?:)?\/\//i.test(value) || value.startsWith('/')) {
    return value;
  }

  return getAssetUrl(value);
}

async function loadManifest() {
  if (manifestCache) return manifestCache;

  const response = await fetch(getAssetsBase() + 'models/synty/modular/manifest.json?v=37', {
    cache: 'no-store'
  });

  if (!response.ok) {
    throw new Error('Unable to load Synty manifest: HTTP ' + response.status);
  }

  manifestCache = await response.json();
  return manifestCache;
}

function loadFbx(fbxLoader, path) {
  return new Promise((resolve, reject) => {
    const assetUrl = resolveAssetUrl(path);
    fbxLoader.load(
      assetUrl,
      (scene) => resolve({ scene, url: assetUrl }),
      undefined,
      (error) => reject({ error, url: assetUrl })
    );
  });
}

function loadGltf(gltfLoader, path) {
  return new Promise((resolve, reject) => {
    const assetUrl = resolveAssetUrl(path);
    gltfLoader.load(
      assetUrl,
      (gltf) => resolve({ scene: gltf.scene, url: assetUrl, gltf }),
      undefined,
      (error) => reject({ error, url: assetUrl })
    );
  });
}

async function loadSceneAsset(gltfLoader, fbxLoader, path) {
  if (!path) {
    throw new Error('Empty asset path');
  }

  if (/\.fbx$/i.test(path)) {
    return loadFbx(fbxLoader, path);
  }

  return loadGltf(gltfLoader, path);
}

function cloneMaterial(mesh) {
  if (!mesh.material) return;

  if (Array.isArray(mesh.material)) {
    mesh.material = mesh.material.map((material) => material.clone());
  } else if (mesh.material.clone) {
    mesh.material = mesh.material.clone();
  }
}

function forEachMaterial(mesh, callback) {
  if (!mesh.material) return;

  if (Array.isArray(mesh.material)) {
    mesh.material.forEach(callback);
  } else {
    callback(mesh.material);
  }
}

function setupModel(model, scale = 1) {
  model.scale.setScalar(scale);

  model.traverse((child) => {
    if (!child.isMesh) return;

    child.castShadow = true;
    child.receiveShadow = true;
    cloneMaterial(child);

    forEachMaterial(child, (material) => {
      if ('roughness' in material && (material.roughness ?? 0) < 0.65) {
        material.roughness = 0.65;
      }
      if ('metalness' in material && material.metalness === undefined) {
        material.metalness = 0.05;
      }
      material.needsUpdate = true;
    });
  });

  return model;
}

function applyEyeColor(model, eyeColor) {
  model.traverse((child) => {
    if (!child.isMesh) return;

    cloneMaterial(child);
    forEachMaterial(child, (material) => {
      if (material.color) {
        if (material.map) {
          material.color.copy(eyeColor.clone().lerp(new THREE.Color(0xffffff), 0.25));
        } else {
          material.color.copy(eyeColor.clone().lerp(new THREE.Color(0xffffff), 0.52));
        }
      }
      if ('emissive' in material && material.emissive) {
        material.emissive.copy(eyeColor);
        material.emissiveIntensity = 0.16;
      }
      material.needsUpdate = true;
    });
  });
}

function applyHairColor(model, hairColor) {
  model.traverse((child) => {
    if (!child.isMesh) return;

    cloneMaterial(child);
    forEachMaterial(child, (material) => {
      if (material.map) {
        material.map = null;
      }
      if (material.color) material.color.copy(hairColor);
      material.needsUpdate = true;
    });
  });
}

function applyFaceOverlayColors(model, colors) {
  model.traverse((child) => {
    if (!child.isMesh) return;

    const meshName = slugify(child.name || '');
    cloneMaterial(child);
    forEachMaterial(child, (material) => {
      const materialName = slugify(material.name || '');
      const label = meshName + ' ' + materialName;
      const isHairLike =
        label.includes('hair') ||
        label.includes('eyebrow') ||
        label.includes('beard') ||
        label.includes('mustache') ||
        label.includes('moustache') ||
        label.includes('facial');
      const isSkinLike =
        label.includes('skin') ||
        label.includes('face') ||
        label.includes('head') ||
        label.includes('jaw') ||
        label.includes('cheek');

      if (material.map && isHairLike) {
        material.map = null;
      }

      if (material.color) {
        if (isHairLike) {
          material.color.copy(colors.hair);
        } else if (isSkinLike) {
          material.color.copy(colors.skin);
        } else {
          material.color.copy(colors.hair);
        }
      }
      material.needsUpdate = true;
    });
  });
}

function applySkinPartColors(model, colors) {
  model.traverse((child) => {
    if (!child.isMesh) return;

    cloneMaterial(child);
    forEachMaterial(child, (material) => {
      if (material.map) {
        material.map = null;
      }
      if (material.color) {
        material.color.copy(colors.skin);
      }
      material.needsUpdate = true;
    });
  });
}

function applyNeutralHeadColors(model, colors) {
  model.traverse((child) => {
    if (!child.isMesh) return;

    cloneMaterial(child);
    forEachMaterial(child, (material) => {
      if (material.map) {
        material.map = null;
      }
      if (material.color) {
        material.color.copy(colors.skin);
      }
      material.needsUpdate = true;
    });
  });
}

function applyBodyColors(model, colors) {
  model.traverse((child) => {
    if (!child.isMesh) return;

    const meshName = slugify(child.name || '');
    cloneMaterial(child);

    forEachMaterial(child, (material) => {
      const materialName = slugify(material.name || '');
      const label = meshName + ' ' + materialName;
      const isHairLike = label.includes('hair') || label.includes('eyebrow') || label.includes('beard');
      const isEyeLike = label.includes('eye') || label.includes('iris');

      if (material.color && isHairLike) {
        material.color.copy(colors.hair);
      } else if (material.color && isEyeLike) {
        material.color.copy(colors.eye);
      } else if (material.color) {
        material.color.copy(colors.skin);
      }

      material.needsUpdate = true;
    });
  });
}

function applyOutfitColors(model, colors, accentColor) {
  const accent = new THREE.Color(accentColor);

  model.traverse((child) => {
    if (!child.isMesh) return;

    const meshName = slugify(child.name || '');
    cloneMaterial(child);

    forEachMaterial(child, (material) => {
      const materialName = slugify(material.name || '');
      const label = meshName + ' ' + materialName;
      const isHairLike =
        label.includes('hair') ||
        label.includes('eyebrow') ||
        label.includes('beard') ||
        label.includes('mustache') ||
        label.includes('moustache') ||
        label.includes('facial');
      const isEyeLike = label.includes('eye') || label.includes('iris');
      const isSkinLike =
        label.includes('skin') ||
        label.includes('regular') ||
        label.includes('face') ||
        label.includes('hand') ||
        label.includes('arm') ||
        label.includes('leg') ||
        label.includes('foot');

      if (material.map && isHairLike) {
        material.map = null;
      }

      if (material.color && isHairLike) {
        material.color.copy(colors.hair);
      } else if (material.color && isEyeLike) {
        material.color.copy(colors.eye);
      } else if (material.color && isSkinLike) {
        material.color.copy(colors.skin);
      } else if (material.color) {
        material.color.lerp(accent, 0.015);
      }

      if (!isSkinLike) {
        material.side = THREE.DoubleSide;
        material.polygonOffset = true;
        material.polygonOffsetFactor = -1;
        material.polygonOffsetUnits = -1;
      }

      material.needsUpdate = true;
    });
  });
}

function makeLoadingLabel(container) {
  const label = document.createElement('div');
  label.textContent = 'Chargement du personnage 3D...';
  label.style.cssText = [
    'position:absolute',
    'top:50%',
    'left:50%',
    'transform:translate(-50%,-50%)',
    'z-index:5',
    'padding:8px 12px',
    'border-radius:10px',
    'background:rgba(11,14,23,.78)',
    'border:1px solid rgba(212,175,55,.25)',
    'color:#d4af37',
    'font-size:13px',
    'font-weight:600',
    'pointer-events:none',
    'white-space:nowrap'
  ].join(';');

  container.appendChild(label);
  return label;
}

function createDecorMaterial(color, options = {}) {
  const tint = color instanceof THREE.Color ? color : new THREE.Color(color);
  const material = new THREE.MeshStandardMaterial({
    color: tint,
    roughness: options.roughness ?? 0.82,
    metalness: options.metalness ?? 0.08
  });

  if (options.emissive) {
    material.emissive = options.emissive instanceof THREE.Color
      ? options.emissive.clone()
      : new THREE.Color(options.emissive);
    material.emissiveIntensity = options.emissiveIntensity ?? 0.2;
  }

  if (options.transparent) {
    material.transparent = true;
    material.opacity = options.opacity ?? 0.7;
  }

  return material;
}

function flagDecorGroup(group, castShadow) {
  group.traverse((child) => {
    if (!child.isMesh) return;
    child.castShadow = !!castShadow;
    child.receiveShadow = true;
  });
}

function addIdentityDecor(scene, accentColor, isThumb) {
  const accent = new THREE.Color(accentColor);
  const stone = new THREE.Color(0x2f3c55);
  const group = new THREE.Group();

  const podium = new THREE.Mesh(
    new THREE.CylinderGeometry(1.48, 1.66, 0.2, 40),
    createDecorMaterial(stone.clone().lerp(accent, 0.08), { roughness: 0.9 })
  );
  podium.position.y = -0.1;
  group.add(podium);

  const ring = new THREE.Mesh(
    new THREE.TorusGeometry(1.18, 0.05, 14, 54),
    createDecorMaterial(accent.clone().lerp(new THREE.Color(0xffffff), 0.15), {
      emissive: accent,
      emissiveIntensity: isThumb ? 0.22 : 0.36,
      roughness: 0.55,
      metalness: 0.18
    })
  );
  ring.rotation.x = Math.PI / 2;
  ring.position.y = 0.02;
  group.add(ring);

  const backdrop = new THREE.Mesh(
    new THREE.BoxGeometry(4.4, 3.2, 0.12),
    createDecorMaterial(stone.clone().lerp(accent, 0.12), {
      emissive: accent.clone().multiplyScalar(0.24),
      emissiveIntensity: isThumb ? 0.14 : 0.22,
      roughness: 0.9,
      metalness: 0.03
    })
  );
  backdrop.position.set(0, 1.45, -1.85);
  group.add(backdrop);

  if (!isThumb) {
    const leftPillar = new THREE.Mesh(
      new THREE.BoxGeometry(0.34, 2.4, 0.34),
      createDecorMaterial(stone.clone().lerp(accent, 0.1))
    );
    leftPillar.position.set(-1.52, 1.2, -1.28);
    group.add(leftPillar);

    const rightPillar = leftPillar.clone();
    rightPillar.position.x = 1.52;
    group.add(rightPillar);
  }

  flagDecorGroup(group, !isThumb);
  scene.add(group);
}

function addAppearanceDecor(scene, accentColor, isThumb) {
  const accent = new THREE.Color(accentColor);
  const stone = new THREE.Color(0x334056);
  const group = new THREE.Group();

  const platform = new THREE.Mesh(
    new THREE.CylinderGeometry(1.08, 1.2, 0.14, 32),
    createDecorMaterial(stone.clone().lerp(accent, 0.12), { roughness: 0.88 })
  );
  platform.position.y = -0.07;
  group.add(platform);

  const rearPanel = new THREE.Mesh(
    new THREE.BoxGeometry(3.5, 2.7, 0.08),
    createDecorMaterial(stone.clone().lerp(accent, 0.18), {
      emissive: accent.clone().multiplyScalar(0.32),
      emissiveIntensity: isThumb ? 0.12 : 0.22,
      roughness: 0.86
    })
  );
  rearPanel.position.set(0, 1.46, -1.65);
  group.add(rearPanel);

  const columnMaterial = createDecorMaterial(stone.clone().lerp(accent, 0.15), {
    emissive: accent.clone().multiplyScalar(0.32),
    emissiveIntensity: isThumb ? 0.16 : 0.24,
    roughness: 0.64,
    metalness: 0.14
  });

  const leftColumn = new THREE.Mesh(new THREE.CylinderGeometry(0.16, 0.2, 2.45, 16), columnMaterial);
  leftColumn.position.set(-1.12, 1.23, -1.05);
  group.add(leftColumn);

  const rightColumn = leftColumn.clone();
  rightColumn.position.x = 1.12;
  group.add(rightColumn);

  const halo = new THREE.Mesh(
    new THREE.TorusGeometry(isThumb ? 0.66 : 0.84, 0.026, 12, 48),
    createDecorMaterial(accent.clone().lerp(new THREE.Color(0xffffff), 0.24), {
      emissive: accent,
      emissiveIntensity: isThumb ? 0.16 : 0.26,
      roughness: 0.48,
      metalness: 0.12
    })
  );
  halo.rotation.x = Math.PI / 2;
  halo.position.set(0, 1.05, -0.16);
  group.add(halo);

  flagDecorGroup(group, !isThumb);
  scene.add(group);
}

function addGuerrierDecor(scene, accentColor, isThumb) {
  const accent = new THREE.Color(accentColor);
  const stone = new THREE.Color(0x44516a);
  const group = new THREE.Group();

  const arena = new THREE.Mesh(
    new THREE.CylinderGeometry(1.5, 1.78, 0.24, 40),
    createDecorMaterial(stone.clone().lerp(accent, 0.08), { roughness: 0.9 })
  );
  arena.position.y = -0.12;
  group.add(arena);

  const ring = new THREE.Mesh(
    new THREE.TorusGeometry(1.24, 0.05, 12, 48),
    createDecorMaterial(accent.clone().lerp(new THREE.Color(0xffffff), 0.16), {
      emissive: accent,
      emissiveIntensity: isThumb ? 0.2 : 0.32
    })
  );
  ring.rotation.x = Math.PI / 2;
  ring.position.y = 0.03;
  group.add(ring);

  if (!isThumb) {
    const pillarLeft = new THREE.Mesh(new THREE.BoxGeometry(0.34, 2.8, 0.34), createDecorMaterial(stone));
    pillarLeft.position.set(-1.7, 1.4, -1.05);
    group.add(pillarLeft);
    const pillarRight = pillarLeft.clone();
    pillarRight.position.x = 1.7;
    group.add(pillarRight);
  }

  flagDecorGroup(group, !isThumb);
  scene.add(group);
}

function addSentinelleDecor(scene, accentColor, isThumb) {
  const accent = new THREE.Color(0x37b7ff);
  const metal = new THREE.Color(0x233954);
  const group = new THREE.Group();

  const platform = new THREE.Mesh(
    new THREE.CylinderGeometry(1.45, 1.66, 0.18, 30),
    createDecorMaterial(metal, { roughness: 0.62, metalness: 0.22 })
  );
  platform.position.y = -0.09;
  group.add(platform);

  const panelMat = createDecorMaterial(metal.clone().lerp(accent, 0.12), {
    emissive: accent,
    emissiveIntensity: isThumb ? 0.18 : 0.28,
    roughness: 0.36,
    metalness: 0.28
  });

  const panelLeft = new THREE.Mesh(new THREE.BoxGeometry(0.28, 1.9, 0.16), panelMat);
  panelLeft.position.set(-1.22, 1.2, -0.78);
  group.add(panelLeft);

  const panelRight = panelLeft.clone();
  panelRight.position.x = 1.22;
  group.add(panelRight);

  const strip = new THREE.Mesh(
    new THREE.BoxGeometry(2.1, 0.06, 0.08),
    createDecorMaterial(accent.clone().lerp(new THREE.Color(0xffffff), 0.15), {
      emissive: accent,
      emissiveIntensity: isThumb ? 0.24 : 0.36,
      roughness: 0.34,
      metalness: 0.3
    })
  );
  strip.position.set(0, 0.32, -0.72);
  group.add(strip);

  flagDecorGroup(group, !isThumb);
  scene.add(group);
}

function addRoninDecor(scene, accentColor, isThumb) {
  const accent = new THREE.Color(0xc84a3c);
  const wood = new THREE.Color(0x5a3b2b);
  const group = new THREE.Group();

  const platform = new THREE.Mesh(
    new THREE.CylinderGeometry(1.42, 1.58, 0.16, 28),
    createDecorMaterial(wood.clone().lerp(accent, 0.08), { roughness: 0.86 })
  );
  platform.position.y = -0.08;
  group.add(platform);

  const lanternPoleMaterial = createDecorMaterial(wood.clone().lerp(new THREE.Color(0x3b2c22), 0.5), {
    roughness: 0.8,
    metalness: 0.06
  });
  const lanternGlowMaterial = createDecorMaterial(new THREE.Color(0xffc27a), {
    emissive: new THREE.Color(0xff9a4d),
    emissiveIntensity: isThumb ? 0.22 : 0.34,
    roughness: 0.45
  });

  [-1.12, 1.12].forEach((x) => {
    const pole = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.05, 1.2, 12), lanternPoleMaterial);
    pole.position.set(x, 0.72, -0.95);
    group.add(pole);

    const lantern = new THREE.Mesh(new THREE.SphereGeometry(0.12, 14, 12), lanternGlowMaterial);
    lantern.position.set(x, 1.28, -0.95);
    group.add(lantern);
  });

  flagDecorGroup(group, !isThumb);
  scene.add(group);
}

function addOccultisteDecor(scene, accentColor, isThumb) {
  const accent = new THREE.Color(0x8b5cf6);
  const obsidian = new THREE.Color(0x31274e);
  const group = new THREE.Group();

  const dais = new THREE.Mesh(
    new THREE.CylinderGeometry(1.36, 1.56, 0.14, 32),
    createDecorMaterial(obsidian.clone().lerp(accent, 0.08), { roughness: 0.84 })
  );
  dais.position.y = -0.07;
  group.add(dais);

  const sigil = new THREE.Mesh(
    new THREE.TorusGeometry(1.0, 0.04, 12, 54),
    createDecorMaterial(accent.clone().lerp(new THREE.Color(0xffffff), 0.12), {
      emissive: accent,
      emissiveIntensity: isThumb ? 0.26 : 0.42,
      roughness: 0.38
    })
  );
  sigil.rotation.x = Math.PI / 2;
  sigil.position.y = 0.02;
  group.add(sigil);

  const orbMaterial = createDecorMaterial(accent.clone().lerp(new THREE.Color(0xffffff), 0.2), {
    emissive: accent,
    emissiveIntensity: isThumb ? 0.24 : 0.38,
    roughness: 0.3
  });

  [[0, 1.58, -0.92], [-0.94, 1.18, -0.72], [0.94, 1.18, -0.72]].forEach((position) => {
    const orb = new THREE.Mesh(new THREE.SphereGeometry(0.12, 14, 12), orbMaterial);
    orb.position.set(position[0], position[1], position[2]);
    group.add(orb);
  });

  flagDecorGroup(group, !isThumb);
  scene.add(group);
}

function addClassDecor(scene, characterType, accentColor, isThumb) {
  switch (normalizeCharacterType(characterType || 'Guerrier')) {
    case 'sentinelle':
      addSentinelleDecor(scene, accentColor, isThumb);
      return;
    case 'ronin':
      addRoninDecor(scene, accentColor, isThumb);
      return;
    case 'occultiste':
      addOccultisteDecor(scene, accentColor, isThumb);
      return;
    case 'guerrier':
    default:
      addGuerrierDecor(scene, accentColor, isThumb);
      return;
  }
}

function addSceneDecor(scene, previewMode, characterType, accentColor, isThumb) {
  const mode = String(previewMode || '').toLowerCase();

  if (mode === 'appearance' || mode === 'thumb-head') {
    addAppearanceDecor(scene, accentColor, isThumb);
    return;
  }

  if (mode === 'identity' || mode === 'thumb-body') {
    addIdentityDecor(scene, accentColor, isThumb);
    return;
  }

  addClassDecor(scene, characterType, accentColor, isThumb);
}



function hideForestGridHelpers(scene, previewMode) {
  if (String(previewMode || '').toLowerCase() !== 'class') {
    return;
  }

  scene.traverse((child) => {
    if (child.type === 'GridHelper') {
      child.visible = false;
    }
  });
}

function addForestTexturedGround(scene, previewMode) {
  if (String(previewMode || '').toLowerCase() !== 'class') {
    return;
  }

  if (scene.getObjectByName('ForestTexturedGround')) {
    return;
  }

  const textureLoader = new THREE.TextureLoader();

  const grassTexture = textureLoader.load('/assets/textures/environments/grass01.png', (texture) => {
    texture.wrapS = THREE.RepeatWrapping;
    texture.wrapT = THREE.RepeatWrapping;
    texture.repeat.set(3.2, 3.2);

    if ('SRGBColorSpace' in THREE) {
      texture.colorSpace = THREE.SRGBColorSpace;
    } else if ('sRGBEncoding' in THREE) {
      texture.encoding = THREE.sRGBEncoding;
    }

    texture.needsUpdate = true;
  });

  const groundMaterial = new THREE.MeshStandardMaterial({
    map: grassTexture,
    color: 0x4f5f2f,
    roughness: 0.95,
    metalness: 0.0
  });

  const ground = new THREE.Mesh(
    new THREE.PlaneGeometry(18, 18, 1, 1),
    groundMaterial
  );

  ground.name = 'ForestTexturedGround';
  ground.rotation.x = -Math.PI / 2;
  ground.position.set(0, -0.075, -1.1);
  ground.receiveShadow = true;

  scene.add(ground);
}

function removeClassDecorRings(scene, previewMode) {
  if (String(previewMode || '').toLowerCase() !== 'class') {
    return;
  }

  scene.traverse((child) => {
    const name = String(child.name || '').toLowerCase();
    const geometryType = child.geometry ? String(child.geometry.type || '').toLowerCase() : '';

    if (
      child.type === 'GridHelper' ||
      geometryType.includes('torus') ||
      name.includes('ring') ||
      name.includes('circle') ||
      name.includes('platform') ||
      name.includes('portal') ||
      name.includes('socle')
    ) {
      child.visible = false;
    }
  });
}

function addForestGrassSprites(scene, previewMode) {
  if (String(previewMode || '').toLowerCase() !== 'class') {
    return;
  }

  if (scene.getObjectByName('ForestGrassSprites')) {
    return;
  }

  const group = new THREE.Group();
  group.name = 'ForestGrassSprites';

  const textureLoader = new THREE.TextureLoader();
  const grassTexture = textureLoader.load('/assets/textures/environments/grassmesh.png', (texture) => {
    if ('SRGBColorSpace' in THREE) {
      texture.colorSpace = THREE.SRGBColorSpace;
    } else if ('sRGBEncoding' in THREE) {
      texture.encoding = THREE.sRGBEncoding;
    }
    texture.needsUpdate = true;
  });

  const grassMaterial = new THREE.MeshBasicMaterial({
    map: grassTexture,
    transparent: true,
    alphaTest: 0.35,
    depthWrite: false,
    side: THREE.DoubleSide
  });

  const positions = [
    [-4.8, -0.06,  0.7, 0.75],
    [-3.9, -0.06,  1.3, 0.95],
    [-3.2, -0.06, -0.2, 0.65],
    [-2.4, -0.06,  1.8, 1.05],
    [-1.6, -0.06,  0.9, 0.75],
    [-0.8, -0.06,  1.6, 0.85],
    [ 0.8, -0.06,  1.5, 0.85],
    [ 1.6, -0.06,  0.8, 0.75],
    [ 2.5, -0.06,  1.7, 1.05],
    [ 3.2, -0.06, -0.1, 0.7],
    [ 3.9, -0.06,  1.1, 0.95],
    [ 4.8, -0.06,  0.6, 0.75],

    [-5.2, -0.06, -2.0, 1.05],
    [-4.2, -0.06, -3.0, 1.2],
    [-2.8, -0.06, -2.6, 0.95],
    [-1.2, -0.06, -3.4, 1.15],
    [ 1.2, -0.06, -3.2, 1.15],
    [ 2.8, -0.06, -2.5, 0.95],
    [ 4.2, -0.06, -3.0, 1.2],
    [ 5.2, -0.06, -2.0, 1.05]
  ];

  positions.forEach(([x, y, z, scale], index) => {
    const blade = new THREE.Mesh(
      new THREE.PlaneGeometry(0.85 * scale, 0.85 * scale),
      grassMaterial
    );

    blade.name = `ForestGrass_${index}`;
    blade.position.set(x, y + 0.32 * scale, z);
    blade.rotation.y = (index * 0.73) % Math.PI;
    blade.renderOrder = 2;

    group.add(blade);

    const bladeCross = blade.clone();
    bladeCross.name = `ForestGrassCross_${index}`;
    bladeCross.rotation.y = blade.rotation.y + Math.PI / 2;
    group.add(bladeCross);
  });

  scene.add(group);
}

async function applyBodyMotionTest(fbxLoader, root, options = {}, previewMode = 'class', runtime = null) {
  const mode = String(previewMode || '').toLowerCase();

  if (mode !== 'class' && mode !== 'identity') {
    return;
  }

  if (!root || root.userData.pixelVerseMotionStarted) {
    return;
  }

  root.userData.pixelVerseMotionStarted = true;

  if (mode === 'identity') {
    startFallbackIdleMotion(root, 0.01);
    return;
  }

}

function startFallbackIdleMotion(root, amount = 0.018) {
  if (!root || root.userData.pixelVerseFallbackMotionStarted) {
    return;
  }

  root.userData.pixelVerseFallbackMotionStarted = true;

  const start = performance.now();
  const baseY = root.position.y;
  const baseRotY = root.rotation.y;

  function tickFallback() {
    if (!root.parent) {
      return;
    }

    const t = (performance.now() - start) / 1000;

    root.position.y = baseY + Math.sin(t * 2.2) * amount;
    root.rotation.y = baseRotY + Math.sin(t * 0.8) * 0.018;

    requestAnimationFrame(tickFallback);
  }

  tickFallback();
}

function applyUnityForestBackground(scene, previewMode, characterType = 'guerrier') {
  if (String(previewMode || '').toLowerCase() !== 'class') {
    return;
  }

  const type = String(characterType || '').toLowerCase();

  let fogColor = 0x10140d;
  let fogNear = 3.2;
  let fogFar = 12.5;
  let glowColor = 0xffd27a;
  let glowIntensity = 0.7;

  if (type.includes('occult')) {
    fogColor = 0x090512;
    fogNear = 2.2;
    fogFar = 8.5;
    glowColor = 0x8b5cf6;
    glowIntensity = 1.15;
  } else if (type.includes('sentinelle')) {
    fogColor = 0x06111f;
    fogNear = 2.8;
    fogFar = 10.5;
    glowColor = 0x38bdf8;
    glowIntensity = 0.95;
  } else if (type.includes('ronin')) {
    fogColor = 0x160807;
    fogNear = 2.8;
    fogFar = 10.5;
    glowColor = 0xf97316;
    glowIntensity = 0.85;
  }

  scene.fog = new THREE.Fog(new THREE.Color(fogColor), fogNear, fogFar);

  const textureLoader = new THREE.TextureLoader();
  textureLoader.load(
    '/assets/img/environments/fantasy_forest_bg.png',
    (texture) => {
      if ('SRGBColorSpace' in THREE) {
        texture.colorSpace = THREE.SRGBColorSpace;
      } else if ('sRGBEncoding' in THREE) {
        texture.encoding = THREE.sRGBEncoding;
      }

      texture.name = 'FantasyForestUnityBackground';
      scene.background = texture;
      console.log('[PixelVerse] Fond Unity forÃªt appliquÃ©');
    },
    undefined,
    (error) => {
      console.warn('[PixelVerse] Fond Unity forÃªt introuvable', error);
    }
  );

  const glow = new THREE.PointLight(glowColor, glowIntensity, 8);
  glow.name = 'ClassForestGlow';
  glow.position.set(0, 2.4, 1.8);
  scene.add(glow);

  hideForestGridHelpers(scene, previewMode);
  removeClassDecorRings(scene, previewMode);
  addForestTexturedGround(scene, previewMode);
  addForestGrassSprites(scene, previewMode);
}
function buildScene(container, accentColor, previewMode = 'class', characterType = 'guerrier') {
  const isThumb = String(previewMode || '').toLowerCase().startsWith('thumb');
  const scene = new THREE.Scene();
  scene.background = new THREE.Color(isThumb ? 0x10192a : 0x0f1726);
  scene.fog = new THREE.Fog(scene.background, isThumb ? 4.2 : 5.8, isThumb ? 12.5 : 20);

  const width = container.clientWidth || 400;
  const height = container.clientHeight || 320;

  const camera = new THREE.PerspectiveCamera(38, width / height, 0.01, 100);
  camera.position.set(0, 1.55, 4);

  const renderer = new THREE.WebGLRenderer({
    antialias: true,
    alpha: false
  });

  renderer.setSize(width, height);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, isThumb ? 1 : 2));
  renderer.shadowMap.enabled = !isThumb;
  if (!isThumb) {
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
  }
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.08;

  container.appendChild(renderer.domElement);

  scene.add(new THREE.AmbientLight(0xffffff, isThumb ? 0.78 : 0.74));

  const keyLight = new THREE.DirectionalLight(0xfff1d6, isThumb ? 1.36 : 1.28);
  keyLight.position.set(3, 5, 4);
  keyLight.castShadow = !isThumb;
  if (!isThumb) {
    keyLight.shadow.mapSize.set(1024, 1024);
  }
  scene.add(keyLight);

  const fillLight = new THREE.DirectionalLight(0x9ec5ff, isThumb ? 0.62 : 0.54);
  fillLight.position.set(-3, 3, -2);
  scene.add(fillLight);

  const rimLight = new THREE.PointLight(accentColor, isThumb ? 1.08 : 0.94, 10);
  rimLight.position.set(0, 2.6, -3.4);
  scene.add(rimLight);

  const useExternalEnvironment = String(previewMode || '').toLowerCase() === 'class';
  if (!useExternalEnvironment) {
    addSceneDecor(scene, previewMode, characterType, accentColor, isThumb);
  }

  if (!isThumb) {
    const ground = new THREE.Mesh(
      new THREE.CircleGeometry(4, 64),
      new THREE.MeshStandardMaterial({
        color: 0x111827,
        roughness: 0.9,
        metalness: 0.04
      })
    );
    ground.rotation.x = -Math.PI / 2;
    ground.position.y = 0;
    ground.receiveShadow = true;
    scene.add(ground);

    const grid = new THREE.GridHelper(7, 28, accentColor, 0x1f2937);
    grid.material.opacity = 0.34;
    grid.material.transparent = true;
    grid.position.y = 0.01;
    scene.add(grid);
  }

  const controls = new OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true;
  controls.dampingFactor = 0.08;
  controls.enablePan = false;
  controls.minDistance = 1.2;
  controls.maxDistance = 8;
  controls.maxPolarAngle = Math.PI / 2 - 0.04;
  controls.target.set(0, 1.1, 0);
  controls.autoRotate = !isThumb;
  controls.autoRotateSpeed = 1;

  return { scene, camera, renderer, controls };
}

function normalizeAndCenter(root, wantedHeight = 2.35) {
  root.updateMatrixWorld(true);

  const box = new THREE.Box3().setFromObject(root);
  const size = new THREE.Vector3();
  const center = new THREE.Vector3();

  box.getSize(size);
  box.getCenter(center);

  if (!Number.isFinite(size.y) || size.y <= 0) return;

  const ratio = wantedHeight / size.y;
  root.scale.multiplyScalar(ratio);
  root.position.set(-center.x * ratio, -box.min.y * ratio, -center.z * ratio);
  root.updateMatrixWorld(true);
}

function getCharacterLift(previewMode = 'class') {
  return 0;
}

function findFocusPoint(root, needles, options = {}) {
  const minY = Number.isFinite(options.minY) ? options.minY : -Infinity;
  const maxY = Number.isFinite(options.maxY) ? options.maxY : Infinity;
  const worldPosition = new THREE.Vector3();
  const matches = [];

  root.traverse((child) => {
    const slug = slugify(child.name || '');
    if (!slug) return;

    if (needles.some((needle) => slug === needle || slug.includes(needle))) {
      child.getWorldPosition(worldPosition);
      if (worldPosition.y >= minY && worldPosition.y <= maxY) {
        matches.push(worldPosition.clone());
      }
    }
  });

  if (!matches.length) return null;
  matches.sort((left, right) => right.y - left.y);
  return matches[0];
}


async function addFantasyForestEnvironment(gltfLoader, scene) {
  try {
    const gltf = await gltfLoader.loadAsync('/assets/models/environments/fantasy_forest_scene.glb');
    const rawEnv = gltf.scene;

    rawEnv.name = 'FantasyForestRaw';

    rawEnv.traverse((child) => {
      if (child.isMesh) {
        child.castShadow = false;
        child.receiveShadow = true;

        if (child.material) {
          child.material.side = THREE.DoubleSide;
          child.material.depthWrite = true;
          child.material.needsUpdate = true;
        }
      }
    });

    rawEnv.updateMatrixWorld(true);

    const box = new THREE.Box3().setFromObject(rawEnv);
    const size = new THREE.Vector3();
    const center = new THREE.Vector3();

    box.getSize(size);
    box.getCenter(center);

    if (!Number.isFinite(size.x) || size.length() <= 0.001) {
      console.warn('[PixelVerse] DÃ©cor forÃªt chargÃ© mais bounding box invalide', size);
      scene.add(rawEnv);
      return;
    }

    // On recentre l'export Unity autour de son vrai centre
    rawEnv.position.x -= center.x;
    rawEnv.position.y -= box.min.y;
    rawEnv.position.z -= center.z;

    const envGroup = new THREE.Group();
    envGroup.name = 'FantasyForestEnvironment';
    envGroup.add(rawEnv);

    // Taille visible dans le viewer
    const maxHorizontalSize = Math.max(size.x, size.z, 0.001);
    const targetWidth = 18;
    const scale = targetWidth / maxHorizontalSize;

    envGroup.scale.setScalar(scale);

    // Placement derriÃ¨re le personnage
    envGroup.position.set(0, -0.32, -5.2);
    envGroup.rotation.set(0, Math.PI, 0);

    scene.add(envGroup);

    console.log('[PixelVerse] DÃ©cor forÃªt chargÃ© et recentrÃ©', {
      originalSize: size.toArray(),
      originalCenter: center.toArray(),
      scale,
      position: envGroup.position.toArray()
    });
  } catch (error) {
    console.warn('[PixelVerse] Impossible de charger le dÃ©cor forÃªt', error);
  }
}
function fitCameraToCharacter(camera, controls, object, mode = 'full') {
  object.updateMatrixWorld(true);

  const box = new THREE.Box3().setFromObject(object);
  const size = new THREE.Vector3();
  const center = new THREE.Vector3();
  box.getSize(size);
  box.getCenter(center);

  if (!Number.isFinite(size.y) || size.y <= 0) {
    camera.position.set(0, 1.45, 4.2);
    controls.target.set(0, 1.05, 0);
    controls.update();
    return;
  }

  const normalizedMode = String(mode).toLowerCase();
  if (normalizedMode === 'head' || normalizedMode === 'portrait') {
    const isPortrait = normalizedMode === 'portrait';
    const eyePoint = findFocusPoint(object, ['eye', 'eyes'], { minY: box.min.y + size.y * 0.66 });
    const browPoint = findFocusPoint(object, ['eyebrow', 'brow'], { minY: box.min.y + size.y * 0.66 });
    const headPoint = findFocusPoint(object, ['head', 'face'], { minY: box.min.y + size.y * 0.62 });
    const target = eyePoint || browPoint || headPoint || new THREE.Vector3(center.x, box.min.y + size.y * (isPortrait ? 0.76 : 0.82), center.z);

    if (isPortrait) {
      const shoulderAnchorY = box.min.y + size.y * 0.78;
      target.y = THREE.MathUtils.lerp(target.y, shoulderAnchorY, 0.04);
    }

    target.x = THREE.MathUtils.clamp(target.x, center.x - size.x * 0.08, center.x + size.x * 0.08);
    target.y = THREE.MathUtils.clamp(target.y, box.min.y + size.y * (isPortrait ? 0.7 : 0.68), box.max.y - size.y * 0.12);
    target.z = center.z;

    const distance = isPortrait
      ? Math.max(1.62, size.y * 0.66, size.x * 1.26)
      : Math.max(0.95, size.y * 0.34, size.x * 0.95);
    camera.position.set(target.x, target.y + size.y * (isPortrait ? 0.015 : 0.02), target.z + distance);
    camera.updateProjectionMatrix();

    controls.target.copy(target);
    controls.autoRotate = false;
    controls.minDistance = isPortrait ? 1.05 : 0.75;
    controls.maxDistance = Math.max(isPortrait ? 3.6 : 2.8, distance * 2.2);
    controls.update();
    camera.lookAt(target);
    return;
  }

  const target = new THREE.Vector3(center.x, box.min.y + size.y * 0.56, center.z);
  const fov = THREE.MathUtils.degToRad(camera.fov);
  const fitHeightDistance = size.y / (2 * Math.tan(fov / 2));
  const fitWidthDistance = size.x / (2 * Math.tan(fov / 2) * camera.aspect);
  const distance = Math.max(fitHeightDistance, fitWidthDistance, Math.max(size.x, size.y, size.z) * 1.42, 3.1);

  camera.position.set(target.x, target.y + 0.16, target.z + distance * 1.16);
  camera.updateProjectionMatrix();

  controls.target.copy(target);
  controls.autoRotate = true;
  controls.minDistance = Math.max(1.2, distance * 0.42);
  controls.maxDistance = Math.max(7, distance * 2.2);
  controls.update();
  camera.lookAt(target);
}

function makeFallbackCharacter(colors, accentColor, previewMode) {
  const group = new THREE.Group();
  const bodyMaterial = new THREE.MeshStandardMaterial({
    color: colors.skin,
    roughness: 0.75,
    metalness: 0.05
  });
  const accentMaterial = new THREE.MeshStandardMaterial({
    color: new THREE.Color(accentColor),
    roughness: 0.82,
    metalness: 0.06
  });
  const hairMaterial = new THREE.MeshStandardMaterial({
    color: colors.hair,
    roughness: 0.84
  });
  const eyeMaterial = new THREE.MeshStandardMaterial({
    color: colors.eye,
    roughness: 0.35
  });

  const showOutfit = previewMode === 'class';

  const torso = new THREE.Mesh(new THREE.CapsuleGeometry(0.42, 0.98, 8, 16), showOutfit ? accentMaterial : bodyMaterial);
  torso.position.y = 1.04;
  group.add(torso);

  const head = new THREE.Mesh(new THREE.SphereGeometry(0.31, 24, 18), bodyMaterial);
  head.position.y = 1.96;
  head.scale.set(0.92, 1.04, 0.87);
  group.add(head);

  const hair = new THREE.Mesh(new THREE.SphereGeometry(0.33, 24, 16), hairMaterial);
  hair.position.set(0, 2.1, -0.01);
  hair.scale.set(0.97, 0.52, 0.9);
  group.add(hair);

  const leftEye = new THREE.Mesh(new THREE.SphereGeometry(0.035, 12, 8), eyeMaterial);
  leftEye.position.set(-0.1, 2.01, 0.26);
  group.add(leftEye);

  const rightEye = leftEye.clone();
  rightEye.position.x = 0.1;
  group.add(rightEye);

  const hip = new THREE.Mesh(new THREE.CylinderGeometry(0.31, 0.38, 0.34, 18), showOutfit ? accentMaterial : bodyMaterial);
  hip.position.y = 0.72;
  group.add(hip);

  const leftLeg = new THREE.Mesh(new THREE.CapsuleGeometry(0.12, 0.74, 8, 12), bodyMaterial);
  leftLeg.position.set(-0.16, 0.44, 0);
  group.add(leftLeg);

  const rightLeg = leftLeg.clone();
  rightLeg.position.x = 0.16;
  group.add(rightLeg);

  const leftArm = new THREE.Mesh(new THREE.CapsuleGeometry(0.09, 0.75, 8, 12), bodyMaterial);
  leftArm.position.set(-0.52, 1.14, 0);
  leftArm.rotation.z = 0.32;
  group.add(leftArm);

  const rightArm = leftArm.clone();
  rightArm.position.x = 0.52;
  rightArm.rotation.z = -0.32;
  group.add(rightArm);

  group.traverse((child) => {
    if (!child.isMesh) return;
    child.castShadow = true;
    child.receiveShadow = true;
  });

  return group;
}

function makePreviewBust(colors) {
  const group = new THREE.Group();
  const skinMaterial = new THREE.MeshStandardMaterial({
    color: colors.skin,
    roughness: 0.78,
    metalness: 0.04
  });

  const chest = new THREE.Mesh(new THREE.CapsuleGeometry(0.78, 0.88, 8, 18), skinMaterial);
  chest.position.y = 1.34;
  chest.scale.set(1.5, 1.12, 1);
  group.add(chest);

  const neck = new THREE.Mesh(new THREE.CylinderGeometry(0.16, 0.18, 0.24, 18), skinMaterial);
  neck.position.y = 1.78;
  group.add(neck);

  const shoulders = new THREE.Mesh(new THREE.CapsuleGeometry(0.22, 1.9, 8, 12), skinMaterial);
  shoulders.rotation.z = Math.PI / 2;
  shoulders.position.y = 1.62;
  shoulders.scale.set(1.28, 1, 0.9);
  group.add(shoulders);

  group.traverse((child) => {
    if (!child.isMesh) return;
    child.castShadow = true;
    child.receiveShadow = true;
  });

  return group;
}

function makeFemalePreviewBust(colors) {
  const group = makePreviewBust(colors);
  group.scale.set(1.14, 1.14, 1);
  group.position.y = 0;
  return group;
}

function makeMalePreviewBust(colors) {
  const group = makePreviewBust(colors);
  group.scale.set(1.24, 1.1, 1.04);
  group.position.y = 0;
  return group;
}

function addAnchoredPreviewBust(root, colors, bustKind = 'male') {
  root.updateMatrixWorld(true);

  const fullBox = new THREE.Box3().setFromObject(root);
  const focus =
    findFocusPoint(root, ['eye', 'eyes']) ||
    findFocusPoint(root, ['eyebrow', 'brow']) ||
    findFocusPoint(root, ['head', 'face']);

  const anchorY = focus ? focus.y : (fullBox.max.y - (fullBox.getSize(new THREE.Vector3()).y * 0.18));
  const bust = bustKind === 'female' ? makeFemalePreviewBust(colors) : makeMalePreviewBust(colors);
  bust.position.y = anchorY - 1.16;
  root.add(bust);
  root.updateMatrixWorld(true);
}

async function addResolvedPart({ gltfLoader, fbxLoader, root, part, role, colors, accentColor, scale = 1, offsetY = 0 }) {
  if (!part) return false;

  const path = resolveModularPath(part);
  if (!path) return false;

  try {
    const { scene } = await loadSceneAsset(gltfLoader, fbxLoader, path);
    const model = setupModel(scene, scale);
    if (offsetY) {
      model.position.y += offsetY;
      model.updateMatrixWorld(true);
    }

    if (role === 'hair') {
      applyHairColor(model, colors.hair);
    } else if (role === 'eyes') {
      applyEyeColor(model, colors.eye);
    } else if (role === 'head') {
      applyNeutralHeadColors(model, colors);
    } else if (role === 'face') {
      applyFaceOverlayColors(model, colors);
    } else if (role === 'skin-part') {
      applySkinPartColors(model, colors);
    } else if (role === 'skin') {
      applyBodyColors(model, colors);
    } else if (role === 'outfit' || role === 'accessory') {
      applyOutfitColors(model, colors, accentColor);
    }

    root.add(model);
    return true;
  } catch (error) {
    console.warn('[SyntyViewer] Unable to load asset', path, error);
    return false;
  }
}

async function loadBodyFamily({ manifest, gltfLoader, fbxLoader, root, family, colors, accentColor, previewMode }) {
  const prefix = BODY_FAMILY_PREFIX[family] || BODY_FAMILY_PREFIX.human;
  const role = family === 'human' && previewMode !== 'class' ? 'skin' : 'outfit';
  let loaded = false;

  for (const category of BODY_CATEGORY_ORDER) {
    const entries = findEntriesByPrefix(manifest, category, prefix);
    for (const entry of entries) {
      loaded = await addResolvedPart({
        gltfLoader,
        fbxLoader,
        root,
        part: entry,
        role,
        colors,
        accentColor
      }) || loaded;
    }
  }

  return loaded;
}

async function loadSpeciesBody({ gltfLoader, fbxLoader, root, bodyStyle, gender, colors, accentColor }) {
  const bodyModel = resolveBodyStyleModel(bodyStyle, gender);
  if (!bodyModel?.path) return false;

  return addResolvedPart({
    gltfLoader,
    fbxLoader,
    root,
    part: bodyModel.path,
    role: 'skin',
    colors,
    accentColor
  });
}

function resolveAppearanceSelection(manifest, options, previewMode = 'appearance') {
  const gender = pickGender(options.gender || 'male');
  const bodyStyle = normalizeBodyStyle(options.bodyStyle, gender);
  const defaults = defaultStyleNumbers(gender, bodyStyle);
  const faceOverlayEnabled = previewMode !== 'identity' && !isFaceOverlayDisabled(options.mouthShape);
  const faceNumber = faceOverlayEnabled ? parseIndexedChoice('face', options.mouthShape, gender, bodyStyle) : null;
  const earNumber = parseIndexedChoice('ear', options.earShape, gender, bodyStyle);
  const browNumber = parseIndexedChoice('brow', options.eyeShape, gender, bodyStyle);
  const noseNumber = parseIndexedChoice('nose', options.noseShape, gender, bodyStyle);
  const hairNumber = parseIndexedChoice('hair', options.hairStyle, gender, bodyStyle);
  const headNumber = defaults.head || (gender === 'female' ? 2 : 1);

  return {
    head: findHumanEntryByStyle(manifest, 'heads', headNumber),
    face: faceNumber ? findHumanEntryByStyle(manifest, 'faces', faceNumber) : null,
    ears: findHumanPairByStyle(manifest, 'ears', earNumber),
    eyes: getPartList(manifest, 'eyes'),
    brows: findHumanPairByStyle(manifest, 'eyebrows', browNumber),
    nose: findHumanEntryByStyle(manifest, 'noses', noseNumber),
    teeth: faceNumber ? findHumanEntryByStyle(manifest, 'teeth', faceNumber) : null,
    tongue: faceNumber ? getPartList(manifest, 'tongue')[0] || null : null,
    hair: findHumanEntryByStyle(manifest, 'hair', hairNumber)
  };
}

async function loadAppearance({ manifest, gltfLoader, fbxLoader, root, options, colors, accentColor, suppressHair = false }) {
  const selection = resolveAppearanceSelection(manifest, options, options.previewMode || 'appearance');
  let loaded = false;

  loaded = await addResolvedPart({ gltfLoader, fbxLoader, root, part: selection.head, role: 'head', colors, accentColor }) || loaded;
  loaded = await addResolvedPart({ gltfLoader, fbxLoader, root, part: selection.face, role: 'face', colors, accentColor }) || loaded;

  for (const ear of selection.ears) {
    loaded = await addResolvedPart({ gltfLoader, fbxLoader, root, part: ear, role: 'skin-part', colors, accentColor }) || loaded;
  }

  for (const eye of selection.eyes) {
    loaded = await addResolvedPart({ gltfLoader, fbxLoader, root, part: eye, role: 'eyes', colors, accentColor }) || loaded;
  }

  for (const brow of selection.brows) {
    loaded = await addResolvedPart({ gltfLoader, fbxLoader, root, part: brow, role: 'hair', colors, accentColor }) || loaded;
  }

  loaded = await addResolvedPart({ gltfLoader, fbxLoader, root, part: selection.nose, role: 'skin-part', colors, accentColor }) || loaded;
  loaded = await addResolvedPart({ gltfLoader, fbxLoader, root, part: selection.teeth, role: 'neutral', colors, accentColor }) || loaded;
  loaded = await addResolvedPart({ gltfLoader, fbxLoader, root, part: selection.tongue, role: 'neutral', colors, accentColor }) || loaded;

  if (!suppressHair) {
    loaded = await addResolvedPart({ gltfLoader, fbxLoader, root, part: selection.hair, role: 'hair', colors, accentColor }) || loaded;
  }

  return loaded;
}

function buildAccessoryList(manifest, variantKey) {
  const variant = OUTFIT_VARIANTS[variantKey];
  if (!variant) return [];

  const entries = [];
  const seen = new Set();

  (variant.accessoryPrefixes || []).forEach((prefix) => {
    findEntriesByPrefix(manifest, 'accessories', prefix).forEach((entry) => {
      const key = String(entry.id || '').toLowerCase();
      if (!seen.has(key)) {
        seen.add(key);
        entries.push(entry);
      }
    });
  });

  (variant.accessoryIds || []).forEach((id) => {
    const entry = findEntryById(manifest, 'accessories', id);
    const key = String(entry?.id || '').toLowerCase();
    if (entry && !seen.has(key)) {
      seen.add(key);
      entries.push(entry);
    }
  });

  return entries;
}

function variantSuppressesHair(manifest, variantKey) {
  const accessories = buildAccessoryList(manifest, variantKey);
  return accessories.some((entry) => /22ahed/i.test(String(entry.id || '')));
}

async function loadVariantAccessories({ manifest, gltfLoader, fbxLoader, root, variantKey, colors, accentColor }) {
  const variant = OUTFIT_VARIANTS[variantKey];
  if (!variant) return false;

  let loaded = false;

  const faceAttachment = variant.faceAttachmentId
    ? findEntryById(manifest, 'faces', variant.faceAttachmentId)
    : null;

  if (faceAttachment) {
    loaded = await addResolvedPart({
      gltfLoader,
      fbxLoader,
      root,
      part: faceAttachment,
      role: 'accessory',
      colors,
      accentColor
    }) || loaded;
  }

  for (const accessory of buildAccessoryList(manifest, variantKey)) {
    loaded = await addResolvedPart({
      gltfLoader,
      fbxLoader,
      root,
      part: accessory,
      role: 'accessory',
      colors,
      accentColor
    }) || loaded;
  }

  return loaded;
}

function getModelScale(options) {
  const scale = Number.parseFloat(options.scale || '1');
  return Number.isFinite(scale) && scale > 0 ? scale : 1;
}

export async function createCharacterViewer(containerId, options = {}) {
  const container = document.getElementById(containerId);
  if (!container) return null;

  if (viewers[containerId]) {
    viewers[containerId].dispose();
    delete viewers[containerId];
  }

  container.innerHTML = '';
  container.style.position = 'relative';
  container.style.overflow = 'hidden';

  const characterType = normalizeCharacterType(options.characterType || 'Guerrier');
  const accentColor = CLASS_ACCENTS[characterType] || CLASS_ACCENTS.guerrier;
  const previewMode = String(options.previewMode || 'class').toLowerCase();
  const scale = getModelScale(options);
  const colors = {
    skin: SKIN_TONES[slugify(options.skinColor || 'Claire')] || SKIN_TONES.claire,
    hair: HAIR_COLORS[slugify(options.hairColor || 'Brun')] || HAIR_COLORS.brun,
    eye: EYE_COLORS[slugify(options.eyeColor || 'Marron')] || EYE_COLORS.marron
  };

  const { scene, camera, renderer, controls } = buildScene(container, accentColor, previewMode, characterType);
  applyUnityForestBackground(scene, previewMode, characterType);
  const loadingLabel = makeLoadingLabel(container);
  const root = new THREE.Group();
  scene.add(root);
  const motionRuntime = {
    clock: new THREE.Clock(),
    mixers: [],
    updaters: []
  };

  const gltfLoader = new GLTFLoader();

  const shouldLoadForestEnvironment = previewMode === 'class';
  if (shouldLoadForestEnvironment) {
    await addFantasyForestEnvironment(gltfLoader, scene);
  }
  const fbxLoader = new FBXLoader();
  let manifest = { parts: {} };

  let loadedSomething = false;

  if (!loadedSomething) {
    try {
      manifest = await loadManifest();
    } catch (error) {
      console.warn('[SyntyViewer] Unable to load manifest, fallback only.', error);
    }
  }

  if (!loadedSomething && previewMode === 'thumb-head') {
    root.add(makePreviewBust(colors));
    loadedSomething = await loadAppearance({
      manifest,
      gltfLoader,
      fbxLoader,
      root,
      options,
      colors,
      accentColor,
      suppressHair: false
    }) || true;
  } else if (!loadedSomething && previewMode === 'thumb-body') {
    loadedSomething = await loadSpeciesBody({
      gltfLoader,
      fbxLoader,
      root,
      bodyStyle: options.bodyStyle,
      gender: options.gender,
      colors,
      accentColor
    }) || loadedSomething;
  } else if (!loadedSomething && previewMode === 'identity') {
    loadedSomething = await loadSpeciesBody({
      gltfLoader,
      fbxLoader,
      root,
      bodyStyle: options.bodyStyle,
      gender: options.gender,
      colors,
      accentColor
    }) || loadedSomething;
  } else if (!loadedSomething && previewMode === 'appearance') {
    const normalizedGender = pickGender(options.gender || 'male');
    const appearanceBody = HEADLESS_APPEARANCE_BODIES[options.bodyStyle] || (normalizedGender === 'female' ? HEADLESS_APPEARANCE_BODIES.body_02 : null);

    if (appearanceBody?.path) {
      loadedSomething = await addResolvedPart({
        gltfLoader,
        fbxLoader,
        root,
        part: appearanceBody.path,
        role: 'skin',
        colors,
        accentColor,
        scale: appearanceBody.scale || 1,
        offsetY: appearanceBody.offsetY || 0
      }) || loadedSomething;

      loadedSomething = await loadAppearance({
        manifest,
        gltfLoader,
        fbxLoader,
        root,
        options,
        colors,
        accentColor,
        suppressHair: false
      }) || loadedSomething;
    } else {
      loadedSomething = await loadBodyFamily({
        manifest,
        gltfLoader,
        fbxLoader,
        root,
        family: 'human',
        colors,
        accentColor,
        previewMode
      }) || loadedSomething;

      loadedSomething = await loadAppearance({
        manifest,
        gltfLoader,
        fbxLoader,
        root,
        options,
        colors,
        accentColor,
        suppressHair: false
      }) || loadedSomething;
    }
  } else if (!loadedSomething) {
    const variantKey = normalizeOutfitVariant(characterType, options.outfitVariant);
    const variant = OUTFIT_VARIANTS[variantKey];
    const bodyFamily = variant?.bodyFamily || CLASS_PRESETS[characterType]?.bodyFamily || 'fantasy';
    const suppressHair = variantSuppressesHair(manifest, variantKey);

    loadedSomething = await loadSpeciesBody({
      gltfLoader,
      fbxLoader,
      root,
      bodyStyle: options.bodyStyle,
      gender: options.gender,
      colors,
      accentColor
    }) || loadedSomething;

    loadedSomething = await loadBodyFamily({
      manifest,
      gltfLoader,
      fbxLoader,
      root,
      family: bodyFamily,
      colors,
      accentColor,
      previewMode
    }) || loadedSomething;

    loadedSomething = await loadAppearance({
      manifest,
      gltfLoader,
      fbxLoader,
      root,
      options,
      colors,
      accentColor,
      suppressHair
    }) || loadedSomething;

    loadedSomething = await loadVariantAccessories({
      manifest,
      gltfLoader,
      fbxLoader,
      root,
      variantKey,
      colors,
      accentColor
    }) || loadedSomething;
  }

  if (!loadedSomething) {
    root.add(makeFallbackCharacter(colors, accentColor, previewMode));
  }

  const wantedHeight = previewMode === 'appearance'
    ? 2.15
    : previewMode === 'thumb-head'
      ? 1.7
      : previewMode === 'thumb-body'
        ? 2.05
        : 2.3;

  normalizeAndCenter(root, wantedHeight);

  if (!root.userData.pixelVerseGeneratedModelLoaded) {
    await applyBodyMotionTest(fbxLoader, root, options, previewMode, motionRuntime);
  }

  const characterLift = getCharacterLift(previewMode);
  if (characterLift) {
    root.position.y += characterLift;
    root.updateMatrixWorld(true);
  }

  if (previewMode === 'thumb-head') {
    camera.position.set(0, 1.42, 1.28);
    controls.target.set(0, 1.34, 0);
    controls.autoRotate = false;
    controls.minDistance = 0.8;
    controls.maxDistance = 2;
    controls.update();
    camera.lookAt(controls.target);
  } else if (previewMode === 'thumb-body') {
    camera.position.set(0, 1.2, 2.55);
    controls.target.set(0, 1.02, 0);
    controls.autoRotate = false;
    controls.minDistance = 1.2;
    controls.maxDistance = 3.8;
    controls.update();
    camera.lookAt(controls.target);
  } else {
    const defaultCameraMode = previewMode === 'appearance' ? 'head' : 'full';
    fitCameraToCharacter(camera, controls, root, options.cameraMode || defaultCameraMode);
  }
  loadingLabel.remove();

  function onResize() {
    const width = container.clientWidth || 400;
    const height = container.clientHeight || 320;
    camera.aspect = width / height;
    camera.updateProjectionMatrix();
    renderer.setSize(width, height);
  }

  window.addEventListener('resize', onResize);
  let frameId = 0;

  function renderFrame() {
    const delta = motionRuntime.clock.getDelta();
    motionRuntime.updaters.forEach((updater) => updater(delta));
    controls.update();
    renderer.render(scene, camera);
  }

  function animate() {
    frameId = window.requestAnimationFrame(animate);
    renderFrame();
  }

  animate();

  const viewer = {
    captureDataUrl() {
      renderFrame();
      return renderer.domElement.toDataURL('image/png');
    },
    dispose() {
      window.cancelAnimationFrame(frameId);
      window.removeEventListener('resize', onResize);
      motionRuntime.mixers.forEach((mixer) => mixer.stopAllAction());

      scene.traverse((object) => {
        if (!object.isMesh) return;
        if (object.geometry) object.geometry.dispose();
        if (object.material) {
          const materials = Array.isArray(object.material) ? object.material : [object.material];
          materials.forEach((material) => material.dispose());
        }
      });

      controls.dispose();
      renderer.dispose();

      if (renderer.domElement && renderer.domElement.parentNode) {
        renderer.domElement.parentNode.removeChild(renderer.domElement);
      }
    }
  };

  viewers[containerId] = viewer;
  return viewer;
}

export function disposeCharacterViewer(containerId) {
  const viewer = viewers[containerId];
  if (!viewer) return;

  viewer.dispose();
  delete viewers[containerId];
}

export function updateCharacterViewer(containerId, newOptions = {}) {
  const container = document.getElementById(containerId);
  if (!container) return;

  const options = {
    characterType: container.dataset.characterType || 'Guerrier',
    skinColor: container.dataset.skinColor || 'Claire',
    gender: container.dataset.gender || 'male',
    bodyStyle: container.dataset.bodyStyle || '',
    earShape: container.dataset.earShape || 'ear_01',
    eyeShape: container.dataset.eyeShape || 'brow_01',
    noseShape: container.dataset.noseShape || 'nose_01',
    mouthShape: container.dataset.mouthShape || 'face_none',
    hairStyle: container.dataset.hairStyle || 'hair_01',
    hairColor: container.dataset.hairColor || 'Brun',
    eyeColor: container.dataset.eyeColor || 'Marron',
    outfitVariant: container.dataset.outfitVariant || 'warrior_full',
    previewMode: container.dataset.previewMode || 'class',
    cameraMode: container.dataset.cameraMode || 'full',
    scale: Number.parseFloat(container.dataset.scale || '1'),
    ...newOptions
  };

  Object.entries(options).forEach(([key, value]) => {
    if (value !== undefined && value !== null) {
      container.dataset[key] = String(value);
    }
  });

  return createCharacterViewer(containerId, options);
}

function waitForAnimationFrame() {
  return new Promise((resolve) => {
    window.requestAnimationFrame(() => resolve());
  });
}

export async function renderCharacterThumbnail(containerId, newOptions = {}) {
  const container = document.getElementById(containerId);
  if (!container) return null;

  const viewer = await updateCharacterViewer(containerId, newOptions);
  if (!viewer || typeof viewer.captureDataUrl !== 'function') {
    return null;
  }

  await waitForAnimationFrame();
  await waitForAnimationFrame();

  let dataUrl = null;
  try {
    dataUrl = viewer.captureDataUrl();
  } catch (error) {
    console.warn('[SyntyViewer] Thumbnail capture failed.', error);
  }

  viewer.dispose();
  if (viewers[containerId] === viewer) {
    delete viewers[containerId];
  }

  if (dataUrl && document.getElementById(containerId) === container) {
    container.innerHTML = '';

    const image = document.createElement('img');
    image.src = dataUrl;
    image.alt = '';
    image.loading = 'lazy';
    image.decoding = 'async';
    image.draggable = false;
    image.style.width = '100%';
    image.style.height = '100%';
    image.style.display = 'block';
    image.style.objectFit = 'cover';

    container.appendChild(image);
  }

  return dataUrl;
}

function initAllViewers() {
  document.querySelectorAll('[data-synty-viewer], [data-three-viewer]').forEach((element) => {
    if (!isElementVisible(element)) {
      return;
    }

    if (element.dataset.choiceField) {
      return;
    }

    if (!element.id) {
      element.id = 'synty-viewer-' + Math.random().toString(36).slice(2);
    }

    createCharacterViewer(element.id, {
      characterType: element.dataset.characterType || 'Guerrier',
      skinColor: element.dataset.skinColor || 'Claire',
      gender: element.dataset.gender || 'male',
      bodyStyle: element.dataset.bodyStyle || '',
      earShape: element.dataset.earShape || 'ear_01',
      eyeShape: element.dataset.eyeShape || 'brow_01',
      noseShape: element.dataset.noseShape || 'nose_01',
      mouthShape: element.dataset.mouthShape || 'face_none',
      hairStyle: element.dataset.hairStyle || 'hair_01',
      hairColor: element.dataset.hairColor || 'Brun',
      eyeColor: element.dataset.eyeColor || 'Marron',
      outfitVariant: element.dataset.outfitVariant || 'warrior_full',
      previewMode: element.dataset.previewMode || 'class',
      cameraMode: element.dataset.cameraMode || 'full',
      scale: Number.parseFloat(element.dataset.scale || '1')
    });
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initAllViewers);
} else {
  initAllViewers();
}

window.PixelVerseSynty3D = {
  createCharacterViewer,
  updateCharacterViewer,
  renderCharacterThumbnail,
  disposeCharacterViewer,
  viewers
};

window.PixelVerse3D = window.PixelVerseSynty3D;
