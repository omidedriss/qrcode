<template>
  <div class="rtl-container logo-panel">
    <p v-if="uploader" class="tag rtl-label">
      {{ $t("upload_or_select_watermark") }}
    </p>
    <!-- <v-file-input
      width="100"
      v-model="localLogoFile"
      outlined
      dense
      clearable
      :label="$t('upload_or_select_watermark')"
      accept="image/*"
      :error-messages="localLogoError"
    ></v-file-input> -->
    <input
      ref="imageInput"
      type="file"
      class="d-none"
      accept="image/*"
      @change="handleImageUpload"
    />
    <v-btn
      block
      class="btn white--text py-2 mt-2 mb-8"
      @click="$refs.imageInput.click()"
      color="#403199"
    >
      <v-icon left>mdi-upload</v-icon>
      {{ $t("upload_or_select_watermark") }}
    </v-btn>
    <div class="watermark-container">
      <v-sheet
        class="pa-1 ma-1 closeicon watermark-item"
        :class="[{ selected: localOptionLogo === 'none' }]"
        @click="selectWatermark('none')"
      >
        <v-icon>mdi-close</v-icon>
      </v-sheet>
      <v-sheet
        v-for="(water, index) in watermarks"
        :key="index"
        :class="[
          'pa-1',
          'ma-1',
          'watermark-item',
          { selected: localOptionLogo === water.value },
        ]"
        @click="selectWatermark(water.value)"
      >
        <img :src="water.src" :alt="water.alt" width="32" />
      </v-sheet>
      <v-sheet
        v-if="localCustomWatermark"
        :class="[
          'pa-1',
          'ma-1',
          'watermark-item',
          { selected: localOptionLogo === 'custom' },
        ]"
        @click="selectWatermark('custom')"
      >
        <img :src="localCustomWatermark" alt="Custom Watermark" width="32" />
      </v-sheet>
    </div>
    <v-switch
      v-model="localNoLogoBg"
      :label="$t('no_logo_background')"
      class="mt-2 rtl-switch"
      color="#403199"
    ></v-switch>
    <v-slider
      v-model="localLogoSize"
      :label="$t('logo_size')"
      min="30"
      max="100"
      thumb-label="always"
      thumb-color="#403199"
      color="#403199"
      class="mt-2"
    ></v-slider>
  </div>
</template>

<script>
export default {
  name: "LogoPanel",
  props: {
    optionLogo: String,
    logoFile: File,
    logoError: String,
    noLogoBg: Boolean,
    logoSize: Number,
    customWatermark: String,
    watermarks: Array,
    uploader: Boolean,
  },
  computed: {
    localOptionLogo: {
      get() {
        return this.optionLogo;
      },
      set(value) {
        this.$emit("update:optionLogo", value);
      },
    },
    localLogoFile: {
      get() {
        return this.logoFile;
      },
      set(value) {
        this.$emit("update:logoFile", value);
      },
    },
    localLogoError: {
      get() {
        return this.logoError;
      },
      set(value) {
        this.$emit("update:logoError", value);
      },
    },
    localNoLogoBg: {
      get() {
        return this.noLogoBg;
      },
      set(value) {
        this.$emit("update:noLogoBg", value);
      },
    },
    localLogoSize: {
      get() {
        return this.logoSize;
      },
      set(value) {
        this.$emit("update:logoSize", value);
      },
    },
    localCustomWatermark: {
      get() {
        return this.customWatermark;
      },
      set(value) {
        this.$emit("update:customWatermark", value);
      },
    },
  },
  methods: {
    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        if (!file.type.startsWith("image/")) {
          this.localLogoError = this.$t("invalid_image_format");
          return;
        }
        this.localLogoFile = file;
        this.localLogoError = "";
        this.$emit("update:logoFile", file);
        const reader = new FileReader();
        reader.onload = (e) => {
          this.localCustomWatermark = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    },
    selectWatermark(value) {
      this.localOptionLogo = value;
    },
  },
};
</script>

<style scoped>
.rtl-container {
  direction: rtl;
  text-align: right;
}

.rtl-label {
  color: #403199;
  text-align: left;
  font-size: 14pt;
  font-weight: 900;
  display: flex;
  justify-content: flex-start;
  justify-self: flex-start;
}
[dir="rtl"].rtl-label {
  color: #403199;
  text-align: right;
  font-size: 14pt;
  font-weight: 900;
  display: flex;
  justify-content: flex-end;
  justify-self: flex-end;
}

:deep(.v-input--switch .v-input--selection-controls__input) {
  flex-direction: row;
  text-align: right;
  display: flex;
}

.rtl-switch .v-input__control {
  justify-content: end;
  display: flex;
}

.rtl-switch {
  display: flex;
  justify-content: end;
}

.ltr {
  direction: ltr;
}

.watermark-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-start;
  gap: 5px;
}

.watermark-item {
  transition: all 0.3s ease;
  cursor: pointer;
  border: 1px solid white;
  position: relative;
  z-index: 10;
  width: 40px;
  height: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
  box-sizing: border-box;
  padding: 0px !important;
  margin: 0px !important;
}
.closeicon {
  display: flex;
  justify-content: center;
  align-items: center;
  box-sizing: border-box;
}
.watermark-item:hover {
  border: 1px solid #413199;
  border-radius: 6px;
  color: #413199;
  box-shadow: 0 0 6px rgba(25, 25, 26, 0.2),
    inset 0px 8px 12px rgba(65, 49, 153, 0.15);
}

.watermark-item.selected {
  border: 2px solid #e9354d;
  border-radius: 6px;
  color: #e9354d;
  background-color: #e9354d4f;
  justify-content: center;
  display: flex;
  box-shadow: 0 0 10px rgba(24, 24, 24, 0.151),
    inset 0px 10px 15px rgba(233, 53, 77, 0.3);
}
.watermark-item img {
  max-width: 32px;
  max-height: 32px;
  object-fit: contain;
}
.v-sheet {
  display: inline-block;
}

/* Fix width and scroll issues on mobile */
@media (max-width: 768px) {
  .rtl-container {
    width: 100% !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }
  
  /* ... rest of styles ... */
}

@media (max-width: 480px) {
  .rtl-container {
    padding: 0 8px !important;
  }
  
  .watermark-container {
    gap: 6px !important;
  }
  
  .watermark-item {
    width: 32px !important;
    height: 32px !important;
    min-width: 32px !important;
    min-height: 32px !important;
  }
  
  .watermark-item img {
    max-width: 28px !important;
    max-height: 28px !important;
  }
}

@media (max-width: 360px) {
  .watermark-container {
    gap: 4px !important;
  }
  
  .watermark-item {
    width: 30px !important;
    height: 30px !important;
    min-width: 30px !important;
    min-height: 30px !important;
  }
  
  .watermark-item img {
    max-width: 26px !important;
    max-height: 26px !important;
  }
}
</style>
