<template>
  <div class="rtl-container colors-panel">
    <p class="rtl-label tag">{{ $t("color_selection") }}</p>
    <v-row>
      <v-col cols="12" sm="6">
        <v-subheader class="rtl-label">{{ $t("background") }}</v-subheader>
        <v-color-picker
          v-model="localBackcolor"
          flat
          mode="hexa"
          class="ltr"
        ></v-color-picker>
        <v-switch
          v-model="localTransparent"
          :label="$t('transparent_background')"
          class="mt-2 rtl-switch"
          color="#403199"
        ></v-switch>
        <v-switch
          v-model="localTransparentCode"
          :label="$t('background_image')"
          class="mt-2 rtl-switch"
          color="#403199"
        ></v-switch>
        <v-expand-transition>
          <div v-if="localTransparentCode">
            <div class="image-editor ltr">
              <v-btn
                class="btn white--text"
                @click="$refs.imageInput.click()"
                color="#403199"
              >
                <v-icon left>mdi-upload</v-icon>
                {{ $t("background_image") }}
              </v-btn>
              <v-btn
                color="#403199"
                class="ml-2 btn white--text"
                :class="{ 'd-none': !localBgImage }"
                @click="exportBackgroundImage"
              >
                <v-icon>mdi-check</v-icon>
              </v-btn>
              <v-btn
                color="#403199"
                class="ml-2 btn white--text"
                :class="{ 'd-none': !localBgImage }"
                @click="removeBackgroundImage"
              >
                <v-icon>mdi-close</v-icon>
              </v-btn>
              <input
                ref="imageInput"
                type="file"
                class="d-none"
                accept="image/*"
                @change="handleImageUpload"
              />
              <div class="cropit-preview mx-auto"></div>
              <v-slider
                v-model="localImageZoom"
                min="0"
                max="1"
                step="0.01"
                color="#403199"
                class="mt-2"
              ></v-slider>
            </div>
            <input type="hidden" v-model="localBgImage" name="bg_image" />
            <v-switch
              v-model="localNegativeQr"
              :label="$t('masked')"
              class="mt-2 rtl-switch"
              :class="{ 'd-none': !localBgImage }"
              color="#403199"
            ></v-switch>
          </div>
        </v-expand-transition>
      </v-col>
      <v-col cols="12" sm="6">
        <v-subheader class="rtl-label">{{ $t("foreground") }}</v-subheader>
        <v-color-picker
          v-model="localFrontcolor"
          flat
          mode="hexa"
          class="ltr"
        ></v-color-picker>
        <v-switch
          v-model="localGradient"
          :label="$t('gradient')"
          class="mt-2 rtl-switch"
          color="#403199"
        ></v-switch>
        <v-expand-transition>
          <div v-if="localGradient">
            <v-subheader class="rtl-label">{{
              $t("second_color")
            }}</v-subheader>
            <v-color-picker
              v-model="localGradientColor"
              flat
              mode="hexa"
              class="ltr"
            ></v-color-picker>
            <v-switch
              v-model="localRadial"
              :label="$t('radial')"
              class="mt-2 rtl-switch"
              color="#403199"
            ></v-switch>
          </div>
        </v-expand-transition>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  name: "ColorsPanel",
  props: {
    backcolor: String,
    frontcolor: String,
    gradientColor: String,
    transparent: Boolean,
    transparentCode: Boolean,
    negativeQr: Boolean,
    gradient: Boolean,
    radial: Boolean,
    bgImage: String,
    imageZoom: Number,
  },
  computed: {
    localBackcolor: {
      get() {
        return this.backcolor;
      },
      set(value) {
        this.$emit("update:backcolor", value);
      },
    },
    localFrontcolor: {
      get() {
        return this.frontcolor;
      },
      set(value) {
        this.$emit("update:frontcolor", value);
      },
    },
    localGradientColor: {
      get() {
        return this.gradientColor;
      },
      set(value) {
        this.$emit("update:gradientColor", value);
      },
    },
    localTransparent: {
      get() {
        return this.transparent;
      },
      set(value) {
        this.$emit("update:transparent", value);
      },
    },
    localTransparentCode: {
      get() {
        return this.transparentCode;
      },
      set(value) {
        this.$emit("update:transparentCode", value);
      },
    },
    localNegativeQr: {
      get() {
        return this.negativeQr;
      },
      set(value) {
        this.$emit("update:negativeQr", value);
      },
    },
    localGradient: {
      get() {
        return this.gradient;
      },
      set(value) {
        this.$emit("update:gradient", value);
      },
    },
    localRadial: {
      get() {
        return this.radial;
      },
      set(value) {
        this.$emit("update:radial", value);
      },
    },
    localBgImage: {
      get() {
        return this.bgImage;
      },
      set(value) {
        this.$emit("update:bgImage", value);
      },
    },
    localImageZoom: {
      get() {
        return this.imageZoom;
      },
      set(value) {
        this.$emit("update:imageZoom", value);
      },
    },
  },
  methods: {
    handleImageUpload(event) {
      const file = event.target.files[0];
      this.$emit("update:bgImage", file);
    },
    exportBackgroundImage() {
  
    },
    removeBackgroundImage() {
      this.$refs.imageInput.value = null;
      this.$emit("update:bgImage", null);
    },
  },
};
</script>

<style scoped>
.ltr {
  direction: ltr;
}
.d-none {
  display: none;
}
.btn {
  border-radius: 10px;
}
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

.pickColor {
  width: 100%;
}
.pattern-container,
.marker-container,
.markerin-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-start;
}

.pattern-item,
.marker-item,
.markerin-item {
  transition: all 0.3s ease;
  cursor: pointer;
  border: 1px solid white;
  position: relative;
  z-index: 1;
}

.pattern-item:hover,
.marker-item:hover,
.markerin-item:hover {
  border: 1px solid #413199;
  border-radius: 6px;
  color: #413199;
  box-shadow: 0 0 6px rgba(25, 25, 26, 0.2),
    inset 0px 8px 12px rgba(65, 49, 153, 0.15);
}

.pattern-item.selected,
.marker-item.selected,
.markerin-item.selected {
  border: 1px solid #e9354d;
  border-radius: 6px;
  color: #e9354d;
  box-shadow: 0 0 10px rgba(24, 24, 24, 0.151),
    inset 0px 10px 15px rgba(233, 53, 77, 0.3);
}

/* حل مشکل عرض و اسکرول در موبایل */
@media (max-width: 768px) {
  .rtl-container {
    width: 100% !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }
  
  .v-row {
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
  }
  
  .v-col {
    width: 100% !important;
    max-width: 100% !important;
    padding: 8px !important;
    box-sizing: border-box !important;
  }
  
  /* تنظیم color picker برای موبایل */
  :deep(.v-color-picker) {
    width: 100% !important;
    max-width: 100% !important;
    overflow: hidden !important;
  }
  
  :deep(.v-color-picker canvas) {
    max-width: 100% !important;
    height: auto !important;
  }
  
  /* تنظیم switches برای موبایل */
  .rtl-switch {
    width: 100% !important;
    max-width: 100% !important;
  }
  
  /* تنظیم slider برای موبایل */
  :deep(.v-slider) {
    width: 100% !important;
    max-width: 100% !important;
  }
  
  /* تنظیم image editor برای موبایل */
  .image-editor {
    width: 100% !important;
    max-width: 100% !important;
    overflow: hidden !important;
  }
  
  .image-editor .btn {
    width: 100% !important;
    margin: 4px 0 !important;
  }
  
  .cropit-preview {
    width: 100% !important;
    max-width: 100% !important;
    overflow: hidden !important;
  }
}

@media (max-width: 480px) {
  .rtl-container {
    padding: 0 8px !important;
  }
  
  .v-col {
    padding: 4px !important;
  }
  
  :deep(.v-color-picker) {
    transform: scale(0.9);
    transform-origin: left top;
  }
}
</style>
