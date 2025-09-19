<template>
  <div class="rtl-container">
    <v-subheader class="rtl-label">{{ $t("pattern") }}</v-subheader>
    <div class="pattern-container">
      <v-sheet
        v-for="(value, marker) in patterns"
        :key="marker"
        :class="[
          'pa-1',
          'ma-1',
          'pattern-item',
          { selected: localPattern === marker },
        ]"
        @click="selectPattern(marker)"
      >
        <svg
          width="38"
          height="38"
          viewBox="0 0 6 6"
          xmlns="http://www.w3.org/2000/svg"
          v-html="value.preview"
          :fill="localPattern === marker ? '#e9354d' : 'currentColor'"
        />
      </v-sheet>
    </div>

    <v-subheader class="rtl-label">{{ $t("marker_outline") }}</v-subheader>
    <div class="marker-container">
      <v-sheet
        v-for="(value, marker) in markers"
        :key="marker"
        :class="[
          'pa-1',
          'ma-1',
          'marker-item',
          { selected: localMarkerOut === marker },
        ]"
        @click="selectMarkerOut(marker)"
      >
        <svg
          width="32"
          height="32"
          viewBox="0 0 14 14"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            :d="value.path.match(/d=\&quot;([^\&quot;]+)\&quot;/)[1]"
            :fill="localMarkerOut === marker ? '#e9354d' : 'currentColor'"
          />
        </svg>
      </v-sheet>
    </div>

    <v-subheader class="rtl-label">{{ $t("marker_center") }}</v-subheader>
    <div class="markerin-container">
      <v-sheet
        v-for="(value, marker) in markersIn"
        :key="marker"
        :class="[
          'pa-1',
          'ma-1',
          'markerin-item',
          { selected: localMarkerIn === marker },
        ]"
        @click="selectMarkerIn(marker)"
      >
        <svg
          width="14"
          height="14"
          viewBox="0 0 6 6"
          :fill="localMarkerIn === marker ? '#e9354d' : 'currentColor'"
          xmlns="http://www.w3.org/2000/svg"
          v-html="value.path"
        ></svg>
      </v-sheet>
    </div>

    <v-row>
      <v-col cols="12" class="px-3">
        <v-switch
          v-model="localMarkersColor"
          :label="$t('custom_markers_colors')"
          class="rtl-switch"
          color="#403199"
        ></v-switch>
      </v-col>
      <v-col cols="12" class="pa-4" v-if="localMarkersColor">
        <v-row>
          <v-col cols="12" class="px-3" md="6">
            <v-subheader class="rtl-label">{{
              $t("marker_outline")
            }}</v-subheader>
            <v-color-picker
              :value="localMarkerOutColor"
              @input="updateMarkerOutColor"
              flat
              mode="hexa"
              class="ltr pickColor"
            ></v-color-picker>
          </v-col>
          <v-col cols="12" md="6" class="pl-3">
            <v-subheader class="rtl-label">{{
              $t("marker_center")
            }}</v-subheader>
            <v-color-picker
              :value="localMarkerInColor"
              @input="updateMarkerInColor"
              flat
              mode="hexa"
              class="ltr pickColor"
            ></v-color-picker>
          </v-col>
        </v-row>
        <v-switch
          v-model="localDifferentMarkersColor"
          :label="$t('different_markers_colors')"
          class="rtl-switch"
          color="#403199"
        ></v-switch>
        <v-row v-if="localDifferentMarkersColor">
          <v-col cols="12" md="6" class="px-3">
            <v-subheader class="rtl-label">{{ $t("top_right") }}</v-subheader>
            <v-color-picker
              :value="localMarkerTopRightOutline"
              @input="updateMarkerTopRightOutline"
              flat
              mode="hexa"
              class="ltr pickColor"
            ></v-color-picker>
          </v-col>
          <v-col cols="12" md="6" class="pl-3">
            <v-subheader class="rtl-label">{{ $t("top_right") }}</v-subheader>
            <v-color-picker
              :value="localMarkerTopRightCenter"
              @input="updateMarkerTopRightCenter"
              flat
              mode="hexa"
              class="ltr pickColor"
            ></v-color-picker>
          </v-col>
          <v-col cols="12" md="6" class="px-3">
            <v-subheader class="rtl-label">{{ $t("bottom_left") }}</v-subheader>
            <v-color-picker
              :value="localMarkerBottomLeftOutline"
              @input="updateMarkerBottomLeftOutline"
              flat
              mode="hexa"
              class="ltr pickColor"
            ></v-color-picker>
          </v-col>
          <v-col cols="12" md="6" class="pl-3">
            <v-subheader class="rtl-label">{{ $t("bottom_left") }}</v-subheader>
            <v-color-picker
              :value="localMarkerBottomLeftCenter"
              @input="updateMarkerBottomLeftCenter"
              flat
              mode="hexa"
              class="ltr pickColor"
            ></v-color-picker>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  name: "DesignPanel",
  props: {
    pattern: String,
    markerOut: String,
    markerIn: String,
    markersColor: Boolean,
    markerOutColor: String,
    markerInColor: String,
    differentMarkersColor: Boolean,
    markerTopRightOutline: String,
    markerTopRightCenter: String,
    markerBottomLeftOutline: String,
    markerBottomLeftCenter: String,
    patterns: Object,
    markers: Object,
    markersIn: Object,
  },
  watch: {
    pattern(newVal) {
      console.log("pattern prop changed to:", newVal, "at:", Date.now());
      this.selectedPattern = newVal;
    },
  },
  mounted() {
    console.log("Patterns prop:", this.patterns);
    console.log("Current pattern:", this.pattern);
    console.log("DesignPanel mounted at:", Date.now());
    console.log("Patterns prop:", this.patterns);
    console.log("Initial pattern prop:", this.pattern);
    console.log("Initial selectedPattern:", this.selectedPattern);
    console.log("Markers:", this.markers);
    console.log("MarkersIn:", this.markersIn);
  },
  computed: {
    localPattern: {
      get() {
        return this.pattern;
      },
      set(value) {
        console.log("Setting localPattern:", value, "at:", Date.now());
        this.$emit("update:pattern", value);
        this.$nextTick(() => {
          console.log(
            "After setting localPattern, current pattern:",
            this.pattern,
            "selectedPattern:",
            this.selectedPattern,
            "at:",
            Date.now()
          );
        });
      },
    },
    localMarkerOut: {
      get() {
        return this.markerOut;
      },
      set(value) {
        console.log("Setting localMarkerOut:", value, "at:", Date.now());
        this.$emit("update:markerOut", value);
      },
    },
    localMarkerIn: {
      get() {
        return this.markerIn;
      },
      set(value) {
        console.log("Setting localMarkerIn:", value, "at:", Date.now());
        this.$emit("update:markerIn", value);
      },
    },
    localMarkersColor: {
      get() {
        return this.markersColor;
      },
      set(value) {
        console.log("Setting localMarkersColor:", value, "at:", Date.now());
        this.$emit("update:markersColor", value);
      },
    },
    localMarkerOutColor: {
      get() {
        return this.markerOutColor;
      },
      set(value) {
        const color = typeof value === "object" ? value.hex : value;
        console.log("Setting localMarkerOutColor:", color, "at:", Date.now());
        this.$emit("update:markerOutColor", color);
      },
    },
    localMarkerInColor: {
      get() {
        return this.markerInColor;
      },
      set(value) {
        const color = typeof value === "object" ? value.hex : value;
        console.log("Setting localMarkerInColor:", color, "at:", Date.now());
        this.$emit("update:markerInColor", color);
      },
    },
    localDifferentMarkersColor: {
      get() {
        return this.differentMarkersColor;
      },
      set(value) {
        console.log(
          "Setting localDifferentMarkersColor:",
          value,
          "at:",
          Date.now()
        );
        this.$emit("update:differentMarkersColor", value);
      },
    },
    localMarkerTopRightOutline: {
      get() {
        return this.markerTopRightOutline;
      },
      set(value) {
        const color = typeof value === "object" ? value.hex : value;
        console.log(
          "Setting localMarkerTopRightOutline:",
          color,
          "at:",
          Date.now()
        );
        this.$emit("update:markerTopRightOutline", color);
      },
    },
    localMarkerTopRightCenter: {
      get() {
        return this.markerTopRightCenter;
      },
      set(value) {
        const color = typeof value === "object" ? value.hex : value;
        console.log(
          "Setting localMarkerTopRightCenter:",
          color,
          "at:",
          Date.now()
        );
        this.$emit("update:markerTopRightCenter", color);
      },
    },
    localMarkerBottomLeftOutline: {
      get() {
        return this.markerBottomLeftOutline;
      },
      set(value) {
        const color = typeof value === "object" ? value.hex : value;
        console.log(
          "Setting localMarkerBottomLeftOutline:",
          color,
          "at:",
          Date.now()
        );
        this.$emit("update:markerBottomLeftOutline", color);
      },
    },
    localMarkerBottomLeftCenter: {
      get() {
        return this.markerBottomLeftCenter;
      },
      set(value) {
        const color = typeof value === "object" ? value.hex : value;
        console.log(
          "Setting localMarkerBottomLeftCenter:",
          color,
          "at:",
          Date.now()
        );
        this.$emit("update:markerBottomLeftCenter", color);
      },
    },
  },
  methods: {
    selectPattern(marker) {
      console.log("Pattern clicked:", marker, "Patterns:", this.patterns);
      this.localPattern = marker;
      this.$nextTick(() => {
        console.log(
          "After pattern selection at:",
          Date.now(),
          "localPattern:",
          this.localPattern
        );
      });
    },
    selectMarkerOut(marker) {
      console.log("Clicked on marker:", marker);
      console.log("Old localMarkerOut:", this.localMarkerOut);
      this.localMarkerOut = marker;
      this.$nextTick(() => {
        console.log("New localMarkerOut:", this.localMarkerOut);
      });
    },
    selectMarkerIn(marker) {
      console.log("MarkerIn clicked:", marker);
      this.localMarkerIn = marker;
    },
    updateMarkerOutColor(value) {
      const color = typeof value === "object" ? value.hex : value;
      console.log("MarkerOutColor changed to:", color);
      this.localMarkerOutColor = color;
    },
    updateMarkerInColor(value) {
      const color = typeof value === "object" ? value.hex : value;
      console.log("MarkerInColor changed to:", color);
      this.localMarkerInColor = color;
    },
    updateMarkerTopRightOutline(value) {
      const color = typeof value === "object" ? value.hex : value;
      console.log("MarkerTopRightOutline changed to:", color);
      this.localMarkerTopRightOutline = color;
    },
    updateMarkerTopRightCenter(value) {
      const color = typeof value === "object" ? value.hex : value;
      console.log("MarkerTopRightCenter changed to:", color);
      this.localMarkerTopRightCenter = color;
    },
    updateMarkerBottomLeftOutline(value) {
      const color = typeof value === "object" ? value.hex : value;
      console.log("MarkerBottomLeftOutline changed to:", color);
      this.localMarkerBottomLeftOutline = color;
    },
    updateMarkerBottomLeftCenter(value) {
      const color = typeof value === "object" ? value.hex : value;
      console.log("MarkerBottomLeftCenter changed to:", color);
      this.localMarkerBottomLeftCenter = color;
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
  box-shadow: 0 -8px 6px rgba(25, 25, 26, 0.2),
    0px 8px 6px rgba(65, 49, 153, 0.15);
}

.pattern-item.selected,
.marker-item.selected,
.markerin-item.selected {
  border: 1px solid #e9354d;
  border-radius: 6px;
  color: #e9354d;
  box-shadow: 0 -10px 10px rgba(24, 24, 24, 0.151),
    0px 10px 10px rgba(233, 53, 77, 0.3);
}

.v-sheet {
  display: inline-block;
}
</style>
