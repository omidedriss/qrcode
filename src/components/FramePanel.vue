<template>
  <div class="rtl-container">
    <p class="rtl-label tag">{{ $t("frame_selection") }}</p>
    <div class="frame-container">
      <v-sheet
        v-for="(value, frame) in frames"
        :key="frame"
        class="pa-1 ma-1 closeicon frame-item"
        :class="[{ selected: localOuterFrame === frame }]"
        @click="selectFrame(frame)"
      >
        <svg
          :width="88"
          :height="
            value.label_size
              ? 84 + value.label_size + 2 + value.label_offset
              : 84
          "
          :viewBox="`0 0 24 ${
            value.label_size
              ? 24 + value.label_size + 2 + value.label_offset
              : 24
          }`"
          :fill="localOuterFrame === frame ? '#e9354d' : 'currentColor'"
          xmlns="http://www.w3.org/2000/svg"
          v-html="value.path"
        ></svg>
      </v-sheet>
    </div>
    <v-row>
      <v-col cols="12" sm="6">
        <v-text-field
          v-model="localFrameLabel"
          :label="$t('frame_label')"
          outlined
          :placeholder="$t('scan_me')"
          dense
        ></v-text-field>
      </v-col>
      <v-col cols="12" sm="6">
        <v-select
          v-model="localLabelFont"
          :items="fonts"
          :label="$t('label_font')"
          outlined
          dense
        ></v-select>
      </v-col>
      <v-col cols="12">
        <v-slider
          v-model="localLabelTextSize"
          :label="$t('text_size')"
          min="10"
          max="100"
          thumb-color="#403199"
          color="#403199"
          thumb-label="always"
        ></v-slider>
      </v-col>
      <v-col cols="12">
        <v-switch
          v-model="localCustomFrameColor"
          :label="$t('custom_frame_color')"
          class="rtl-switch"
          color="#403199"
        ></v-switch>
        <v-expand-transition>
          <v-row v-if="localCustomFrameColor">
            <v-col cols="12" sm="6">
              <v-subheader class="rtl-label">{{
                $t("frame_color")
              }}</v-subheader>
              <v-color-picker
                v-model="localFrameColor"
                flat
                mode="hexa"
                class="ltr pickColor"
              ></v-color-picker>
            </v-col>
          </v-row>
        </v-expand-transition>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  name: "FramePanel",
  props: {
    outerFrame: String,
    frameLabel: String,
    labelFont: String,
    labelTextSize: Number,
    customFrameColor: Boolean,
    frameColor: String,
    frames: Object,
    fonts: Array,
  },
  computed: {
    localOuterFrame: {
      get() {
        return this.outerFrame;
      },
      set(value) {
        this.$emit("update:outerFrame", value);
      },
    },
    localFrameLabel: {
      get() {
        return this.frameLabel;
      },
      set(value) {
        this.$emit("update:frameLabel", value);
      },
    },
    localLabelFont: {
      get() {
        return this.labelFont;
      },
      set(value) {
        this.$emit("update:labelFont", value);
      },
    },
    localLabelTextSize: {
      get() {
        return this.labelTextSize;
      },
      set(value) {
        this.$emit("update:labelTextSize", value);
      },
    },
    localCustomFrameColor: {
      get() {
        return this.customFrameColor;
      },
      set(value) {
        this.$emit("update:customFrameColor", value);
      },
    },
    localFrameColor: {
      get() {
        return this.frameColor;
      },
      set(value) {
        this.$emit("update:frameColor", value);
      },
    },
  },
  methods: {
    selectFrame(frame) {
      this.localOuterFrame = frame;
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

.pickColor {
  width: 100%;
}

.frame-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-start;
  gap: 5px;
  padding: 5px;
  margin-bottom: 20px;
}

.frame-item {
  transition: all 0.3s ease;
  cursor: pointer;
  /* border: 1px solid white; */
  border: 1px solid transparent;
  position: relative;
  z-index: 1;
  width: 97px;
  height: 97px;
  display: flex;
  justify-content: center;
  align-items: center;
  box-sizing: border-box;
  padding: 0px !important;
  margin: 0px !important;
}

.frame-item:hover {
  border: 1px solid #413199;
  border-radius: 6px;
  color: #413199;
  box-shadow: 0 0 6px rgba(25, 25, 26, 0.2),
    inset 0px 8px 12px rgba(65, 49, 153, 0.15);
  transform: scale(1.1);
}

.frame-item.selected {
  border: 2px solid #e9354d;
  border-radius: 6px;
  color: #e9354d;
  background-color: #e9354d23;
  box-shadow: 0 0 10px rgba(24, 24, 24, 0.151),
    inset 0px 10px 15px rgba(233, 53, 77, 0.3);
}
.frame-item svg {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  transition: transform 0.3s ease;
}
.v-sheet {
  display: inline-block;
}
.closeicon {
  display: flex;
  justify-content: center;
  align-items: center;
  box-sizing: border-box;
}
@media (max-width: 768px) {
  .frame-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 2px;
    padding: 2px;
    margin-bottom: 8px;
  }

  .frame-item {
    transition: all 0.3s ease;
    cursor: pointer;
    /* border: 1px solid white; */
    border: 1px solid transparent;
    position: relative;
    z-index: 1;
    width: 90px;
    height: 90px;
    display: flex;
    justify-content: center;
    align-items: center;
    box-sizing: border-box;
    padding: 0px !important;
    margin: 0px !important;
  }
  @media (max-width: 375px) {
    @media (max-width: 320px) {
      .frame-item {
        transition: all 0.3s ease;
        cursor: pointer;
        /* border: 1px solid white; */
        border: 1px solid transparent;
        position: relative;
        z-index: 1;
        width: 70px;
        height: 70px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-sizing: border-box;
        padding: 0px !important;
        margin: 0px !important;
      }
    }

    .frame-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: flex-start;
      gap: 2px;
      padding: 2px;
      margin-bottom: 5px;
    }

    .frame-item {
      transition: all 0.3s ease;
      cursor: pointer;
      /* border: 1px solid white; */
      border: 1px solid transparent;
      position: relative;
      z-index: 1;
      width: 80px;
      height: 80px;
      display: flex;
      justify-content: center;
      align-items: center;
      box-sizing: border-box;
      padding: 0px !important;
      margin: 0px !important;
    }
  }

  .frame-item:active {
    border: 1px solid #413199;
    border-radius: 6px;
    color: #413199;
    box-shadow: 0 0 6px rgba(25, 25, 26, 0.2),
      inset 0px 8px 12px rgba(65, 49, 153, 0.15);
    transform: scale(1.05);
  }
}
</style>
