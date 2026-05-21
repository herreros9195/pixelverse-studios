(function () {
  'use strict';

  var form = document.querySelector('[data-character-form]');
  if (!form) return;

  var previewName = document.querySelector('[data-preview-name]');
  var previewMeta = document.querySelector('[data-preview-meta]');
  var previewTags = document.querySelector('[data-preview-tags]');
  var currentStep = 1;
  var totalSteps = form.querySelectorAll('[data-wizard-step]').length || 4;
  var currentAppearanceStep = 1;
  var totalAppearanceSteps = form.querySelectorAll('[data-appearance-panel]').length || 1;
  var lastViewerKey = '';
  var assetRenderSequence = 0;
  var thumbnailConcurrency = 4;

  function getViewerApi() {
    return window.PixelVerseSynty3D || window.PixelVerse3D || null;
  }

  function slugify(value) {
    return (value || '')
      .toString()
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .replace(/[^a-z0-9]/g, '');
  }

  function parseList(value) {
    return (value || '')
      .toString()
      .split(/\s+/)
      .map(slugify)
      .filter(Boolean);
  }

  function isElementVisible(element) {
    if (!element) return false;
    if (element.hidden) return false;
    if (element.closest('[hidden]')) return false;
    if (element.closest('.pv-substep') && !element.closest('.pv-substep.active')) return false;

    return element.getClientRects().length > 0;
  }

  function getValue(name) {
    var checked = form.querySelector('input[name="' + name + '"]:checked');
    if (checked) return checked.value;

    var field = form.querySelector('[name="' + name + '"]');
    return field ? field.value : '';
  }

  function getLabel(name) {
    var checked = form.querySelector('input[name="' + name + '"]:checked');

    if (checked) {
      if (checked.dataset.label) return checked.dataset.label;

      var title = form.querySelector('label[for="' + checked.id + '"] .pv-choice-title');
      if (title) return title.textContent.trim();

      var assetTitle = form.querySelector('label[for="' + checked.id + '"] .pv-asset-title');
      if (assetTitle) return assetTitle.textContent.trim();

      var label = form.querySelector('label[for="' + checked.id + '"]');
      if (label) {
        var text = label.textContent.trim();
        if (text) return text;

        var titled = label.getAttribute('title');
        if (titled) return titled.trim();
      }

      return checked.value;
    }

    var field = form.querySelector('[name="' + name + '"]');
    if (!field) return '';

    if (field.tagName === 'SELECT' && field.selectedIndex >= 0) {
      return field.options[field.selectedIndex].textContent.trim();
    }

    return field.value || '';
  }

  function preferredValueForGender(field, gender) {
    if (!field || !field.dataset) return '';
    if (gender === 'female') return field.dataset.defaultFemale || '';
    return field.dataset.defaultMale || '';
  }

  function syncGenderFilteredRadioGroup(group) {
    if (!group) return false;

    var gender = slugify(getValue('gender') || 'male');
    var changed = false;

    group.querySelectorAll('.pv-asset-option').forEach(function (wrapper) {
      var radio = wrapper.querySelector('input[type="radio"]');
      if (!radio) return;

      var allowed = parseList(radio.dataset.genders || wrapper.dataset.genders || 'male female');
      var isAllowed = allowed.indexOf(gender) !== -1;

      wrapper.hidden = !isAllowed;
      radio.disabled = !isAllowed;
    });

    var current = group.querySelector('input[type="radio"]:checked');
    if (!current || current.disabled) {
      var preferred = preferredValueForGender(group, gender);
      var fallback = group.querySelector('input[type="radio"][value="' + preferred + '"]:not(:disabled)')
        || group.querySelector('input[type="radio"]:not(:disabled)');

      if (fallback) {
        fallback.checked = true;
        changed = true;
      }
    }

    return changed;
  }

  function syncGenderFilteredSelect(field) {
    if (!field) return false;

    var gender = slugify(getValue('gender') || 'male');
    var changed = false;

    Array.prototype.forEach.call(field.options, function (option) {
      if (!option.value) return;

      var allowed = parseList(option.dataset.genders || 'male female');
      var isAllowed = allowed.indexOf(gender) !== -1;

      option.hidden = !isAllowed;
      option.disabled = !isAllowed;
    });

    var current = Array.prototype.find.call(field.options, function (option) {
      return option.value === field.value;
    });

    if (!current || current.disabled) {
      var preferred = preferredValueForGender(field, gender);
      var fallback = Array.prototype.find.call(field.options, function (option) {
        return option.value === preferred && !option.disabled;
      }) || Array.prototype.find.call(field.options, function (option) {
        return option.value && !option.disabled;
      });

      if (fallback) {
        field.value = fallback.value;
        changed = true;
      }
    }

    return changed;
  }

  function syncGenderDependentChoices() {
    var changed = false;

    form.querySelectorAll('select[data-gender-filter="1"]').forEach(function (field) {
      changed = syncGenderFilteredSelect(field) || changed;
    });

    form.querySelectorAll('[data-visual-radio-group]').forEach(function (group) {
      changed = syncGenderFilteredRadioGroup(group) || changed;
    });

    return changed;
  }

  function getSelectedClassFamily() {
    var checked = form.querySelector('input[name="character_type"]:checked');
    return checked ? slugify(checked.dataset.classFamily || 'guerrier') : 'guerrier';
  }

  function syncOutfitVariantChoices() {
    var family = getSelectedClassFamily();
    var changed = false;

    form.querySelectorAll('[data-outfit-option]').forEach(function (wrapper) {
      var families = parseList(wrapper.dataset.families || '');
      var isAllowed = families.indexOf(family) !== -1;
      var radio = wrapper.querySelector('input[name="outfit_variant"]');

      wrapper.hidden = !isAllowed;
      wrapper.classList.toggle('is-disabled', !isAllowed);

      if (radio) {
        radio.disabled = !isAllowed;
      }
    });

    var checked = form.querySelector('input[name="outfit_variant"]:checked');
    if (!checked || checked.disabled) {
      var fallback = form.querySelector('input[name="outfit_variant"]:not(:disabled)');
      if (fallback) {
        fallback.checked = true;
        changed = true;
      }
    }

    return changed;
  }

  function syncDependentChoices() {
    var genderChanged = syncGenderDependentChoices();
    var outfitChanged = syncOutfitVariantChoices();
    return genderChanged || outfitChanged;
  }

  function getPreviewMode() {
    if (currentStep === 1) return 'identity';
    if (currentStep === 2) return 'appearance';
    return 'class';
  }

  function getViewerOptions() {
    return {
      characterType: getValue('character_type') || 'Guerrier',
      skinColor: getValue('skin_color') || 'Claire',
      gender: getValue('gender') || 'male',
      bodyStyle: getValue('body_style') || 'body_04',
      earShape: getValue('ear_shape') || 'ear_01',
      eyeShape: getValue('eye_shape') || 'brow_01',
      noseShape: getValue('nose_shape') || 'nose_01',
      mouthShape: getValue('mouth_shape') || 'face_none',
      hairStyle: getValue('hair_style') || 'hair_01',
      hairColor: getValue('hair_color') || 'Brun',
      eyeColor: getValue('eye_color') || 'Bleu',
      outfitVariant: getValue('outfit_variant') || 'warrior_full',
      previewMode: getPreviewMode(),
      cameraMode: currentStep === 2 ? 'portrait' : 'full'
    };
  }

  function updateViewer(force) {
    var api = getViewerApi();
    var container = document.getElementById('create-three-viewer');
    if (!container) return;

    var options = getViewerOptions();
    var key = JSON.stringify(options);

    Object.keys(options).forEach(function (keyName) {
      container.dataset[keyName] = String(options[keyName]);
    });

    if (!api || typeof api.updateCharacterViewer !== 'function') return;

    if (!force && key === lastViewerKey) return;
    lastViewerKey = key;

    api.updateCharacterViewer('create-three-viewer', options);
  }

  function updateAssetCardViewers(force) {
    var api = getViewerApi();
    if (!api || (typeof api.updateCharacterViewer !== 'function' && typeof api.renderCharacterThumbnail !== 'function')) return;

    var baseOptions = getViewerOptions();
    var renderJobs = [];
    var sequence = ++assetRenderSequence;

    form.querySelectorAll('[data-choice-field][data-choice-value][data-synty-viewer]').forEach(function (viewer) {
      var wrapper = viewer.closest('.pv-asset-option');
      var visible = isElementVisible(viewer) && !(wrapper && wrapper.hidden);

      if (!viewer.id) {
        viewer.id = 'synty-mini-' + Math.random().toString(36).slice(2);
      }

      if (!visible) {
        if (typeof api.disposeCharacterViewer === 'function') {
          api.disposeCharacterViewer(viewer.id);
        }
        viewer.innerHTML = '';
        delete viewer.dataset.renderKey;
        delete viewer.dataset.thumbnailReady;
        return;
      }

      var choiceField = viewer.dataset.choiceField || '';
      var choiceValue = viewer.dataset.choiceValue || '';

      var options = Object.assign({}, baseOptions, {
        previewMode: choiceField === 'body_style'
          ? (viewer.dataset.previewMode || 'thumb-body')
          : 'thumb-head',
        cameraMode: viewer.dataset.cameraMode || 'portrait',
        scale: Number.parseFloat(viewer.dataset.scale || '1')
      });

      if (choiceField === 'mouth_shape') options.mouthShape = choiceValue;
      if (choiceField === 'eye_shape') options.eyeShape = choiceValue;
      if (choiceField === 'body_style') options.bodyStyle = choiceValue;
      if (choiceField === 'ear_shape') options.earShape = choiceValue;
      if (choiceField === 'nose_shape') options.noseShape = choiceValue;
      if (choiceField === 'hair_style') options.hairStyle = choiceValue;

      var renderKey = JSON.stringify(options);
      if (!force && viewer.dataset.renderKey === renderKey) {
        return;
      }

      viewer.dataset.renderKey = renderKey;
      delete viewer.dataset.thumbnailReady;
      renderJobs.push({
        viewer: viewer,
        options: options
      });
    });

    (async function () {
      var nextIndex = 0;

      async function runWorker() {
        while (nextIndex < renderJobs.length) {
          if (sequence !== assetRenderSequence) {
            return;
          }

          var job = renderJobs[nextIndex];
          nextIndex += 1;

          if (typeof api.renderCharacterThumbnail === 'function') {
            await api.renderCharacterThumbnail(job.viewer.id, job.options);
          } else if (typeof api.updateCharacterViewer === 'function') {
            await api.updateCharacterViewer(job.viewer.id, job.options);
          }

          if (sequence !== assetRenderSequence) {
            return;
          }

          job.viewer.dataset.thumbnailReady = '1';
        }
      }

      var workerCount = Math.max(1, Math.min(thumbnailConcurrency, renderJobs.length));
      var workers = [];
      for (var workerIndex = 0; workerIndex < workerCount; workerIndex += 1) {
        workers.push(runWorker());
      }

      await Promise.all(workers);
    })().catch(function (error) {
      console.warn('[SyntyBuilder] Unable to update asset card viewers.', error);
    });
  }

  function updateAppearanceControls() {
    var prev = form.querySelector('[data-appearance-prev]');
    var next = form.querySelector('[data-appearance-next]');
    if (!prev || !next) return;

    prev.textContent = currentAppearanceStep === 1 ? '← Retour - Identite' : '← Etape precedente';
    next.textContent = currentAppearanceStep === totalAppearanceSteps ? 'Suivant - Classe' : 'Suivant';

    form.querySelectorAll('[data-appearance-nav]').forEach(function (button) {
      var step = parseInt(button.dataset.appearanceNav, 10);
      button.classList.toggle('active', step === currentAppearanceStep);
      button.classList.toggle('completed', step < currentAppearanceStep);
    });
  }

  function showAppearanceStep(step) {
    currentAppearanceStep = Math.max(1, Math.min(step, totalAppearanceSteps));

    form.querySelectorAll('[data-appearance-panel]').forEach(function (panel) {
      panel.classList.toggle('active', parseInt(panel.dataset.appearancePanel, 10) === currentAppearanceStep);
    });

    updateAppearanceControls();
    updateViewer(true);
    updateAssetCardViewers(true);
  }

  function setSummaryValue(selector, value) {
    var node = form.querySelector(selector);
    if (node) node.textContent = value || '-';
  }

  function updatePreviewText() {
    var name = getValue('name') || 'Nom du personnage';
    var gender = getLabel('gender') || 'Genre';
    var bodyLabel = getLabel('body_style') || '';
    var classLabel = getLabel('character_type') || 'Guerrier';
    var outfitLabel = getLabel('outfit_variant') || '';

    if (previewName) previewName.textContent = name;
    if (previewMeta) previewMeta.textContent = gender + ' - ' + classLabel;

    if (previewTags) {
      previewTags.innerHTML = '';

      [classLabel, gender, bodyLabel, outfitLabel].forEach(function (value) {
        if (!value) return;

        var tag = document.createElement('span');
        tag.className = 'pv-tag';
        tag.textContent = value;
        previewTags.appendChild(tag);
      });
    }
  }

  function updateSummary() {
    setSummaryValue('[data-summary="name"]', getValue('name') || '-');
    setSummaryValue('[data-summary="gender"]', getLabel('gender') || '-');
    setSummaryValue('[data-summary="body_style"]', getLabel('body_style') || '-');
    setSummaryValue('[data-summary="skin_color"]', getLabel('skin_color') || '-');
    setSummaryValue('[data-summary="mouth_shape"]', getLabel('mouth_shape') || '-');
    setSummaryValue('[data-summary="ear_shape"]', getLabel('ear_shape') || '-');
    setSummaryValue('[data-summary="eye_shape"]', getLabel('eye_shape') || '-');
    setSummaryValue('[data-summary="nose_shape"]', getLabel('nose_shape') || '-');
    setSummaryValue('[data-summary="hair"]', (getLabel('hair_style') || '-') + ' - ' + (getLabel('hair_color') || '-'));
    setSummaryValue('[data-summary="eye_color"]', getLabel('eye_color') || '-');
    setSummaryValue('[data-summary="character_type"]', getLabel('character_type') || '-');
    setSummaryValue('[data-summary="outfit_variant"]', getLabel('outfit_variant') || '-');
  }

  function showStep(step) {
    currentStep = Math.max(1, Math.min(step, totalSteps));

    form.querySelectorAll('[data-wizard-step]').forEach(function (element) {
      element.classList.toggle('active', parseInt(element.dataset.wizardStep, 10) === currentStep);
    });

    form.querySelectorAll('[data-wizard-nav]').forEach(function (element) {
      var navStep = parseInt(element.dataset.wizardNav, 10);
      element.classList.remove('active', 'completed');

      if (navStep === currentStep) {
        element.classList.add('active');
      } else if (navStep < currentStep) {
        element.classList.add('completed');
      }
    });

    if (currentStep === totalSteps) {
      updateSummary();
    }

    if (currentStep === 2) {
      showAppearanceStep(currentAppearanceStep);
    } else {
      updateAppearanceControls();
    }

    updateViewer(true);
    updateAssetCardViewers(true);
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function validateStep(step) {
    var stepElement = form.querySelector('[data-wizard-step="' + step + '"]');
    if (!stepElement) return true;

    var fields = stepElement.querySelectorAll('[required]');
    var valid = true;

    fields.forEach(function (field) {
      if (field.type === 'radio') {
        var groupChecked = stepElement.querySelector('input[name="' + field.name + '"]:checked');
        var groupValid = Boolean(groupChecked);
        field.classList.toggle('is-invalid', !groupValid);
        if (!groupValid) valid = false;
        return;
      }

      if (!field.checkValidity()) {
        field.classList.add('is-invalid');
        valid = false;
      } else {
        field.classList.remove('is-invalid');
      }
    });

    if (!valid) {
      var firstInvalid = stepElement.querySelector('.is-invalid');
      if (firstInvalid) firstInvalid.focus();
    }

    return valid;
  }

  function onFormChange(event) {
    syncDependentChoices();
    updatePreviewText();

    var target = event ? event.target : null;
    var visualFields = [
      'character_type',
      'skin_color',
      'gender',
      'body_style',
      'ear_shape',
      'eye_shape',
      'nose_shape',
      'mouth_shape',
      'hair_style',
      'hair_color',
      'eye_color',
      'outfit_variant'
    ];

    if (!target || visualFields.indexOf(target.name) !== -1) {
      updateViewer(false);
      updateAssetCardViewers(false);
    }

    if (currentStep === totalSteps) {
      updateSummary();
    }

    form.querySelectorAll('.is-invalid').forEach(function (element) {
      if (typeof element.checkValidity === 'function' && element.checkValidity()) {
        element.classList.remove('is-invalid');
      }
    });
  }

  form.querySelectorAll('[data-wizard-next]').forEach(function (button) {
    button.addEventListener('click', function () {
      if (validateStep(currentStep)) {
        showStep(currentStep + 1);
      }
    });
  });

  form.querySelectorAll('[data-wizard-prev]').forEach(function (button) {
    button.addEventListener('click', function () {
      showStep(currentStep - 1);
    });
  });

  form.querySelectorAll('[data-wizard-nav]').forEach(function (button) {
    button.addEventListener('click', function () {
      var targetStep = parseInt(button.dataset.wizardNav, 10);

      if (targetStep <= currentStep) {
        showStep(targetStep);
        return;
      }

      for (var step = currentStep; step < targetStep; step++) {
        if (!validateStep(step)) {
          return;
        }
      }

      showStep(targetStep);
    });
  });

  form.querySelectorAll('[data-appearance-nav]').forEach(function (button) {
    button.addEventListener('click', function () {
      showAppearanceStep(parseInt(button.dataset.appearanceNav, 10));
    });
  });

  form.querySelectorAll('[data-appearance-prev]').forEach(function (button) {
    button.addEventListener('click', function () {
      if (currentAppearanceStep === 1) {
        showStep(1);
        return;
      }

      showAppearanceStep(currentAppearanceStep - 1);
    });
  });

  form.querySelectorAll('[data-appearance-next]').forEach(function (button) {
    button.addEventListener('click', function () {
      if (currentAppearanceStep >= totalAppearanceSteps) {
        if (validateStep(2)) {
          showStep(3);
        }
        return;
      }

      showAppearanceStep(currentAppearanceStep + 1);
    });
  });

  form.addEventListener('input', onFormChange);
  form.addEventListener('change', onFormChange);

  syncDependentChoices();
  updatePreviewText();
  updateAppearanceControls();
  updateViewer(true);
  updateAssetCardViewers(true);
})();
