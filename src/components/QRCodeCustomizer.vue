<template>
  <v-expansion-panels
    v-model="activePanel"
    :accordion="accordion"
    :flat="!accordion"
  >
    <colors-panel
      :backcolor.sync="backcolor"
      :frontcolor.sync="frontcolor"
      :gradient-color.sync="gradientColor"
      :transparent.sync="transparent"
      :transparent-code.sync="transparentCode"
      :negative-qr.sync="negativeQr"
      :gradient.sync="gradient"
      :radial.sync="radial"
      :bg-image.sync="bgImage"
      :image-zoom.sync="imageZoom"
      @update:bgImage="handleImageUpdate"
    />
    <design-panel
      :pattern.sync="pattern"
      :marker-out.sync="markerOut"
      :marker-in.sync="markerIn"
      :markers-color.sync="markersColor"
      :marker-out-color.sync="markerOutColor"
      :marker-in-color.sync="markerInColor"
      :different-markers-color.sync="differentMarkersColor"
      :marker-top-right-outline.sync="markerTopRightOutline"
      :marker-top-right-center.sync="markerTopRightCenter"
      :marker-bottom-left-outline.sync="markerBottomLeftOutline"
      :marker-bottom-left-center.sync="markerBottomLeftCenter"
      :patterns="patterns"
      :markers="markers"
      :markers-in="markersIn"
    />
    <logo-panel
      :option-logo.sync="optionLogo"
      :logo-file.sync="logoFile"
      :logo-error.sync="logoError"
      :no-logo-bg.sync="noLogoBg"
      :logo-size.sync="logoSize"
      :custom-watermark.sync="customWatermark"
      :watermarks="watermarks"
      :uploader="uploader"
      @update:logoFile="handleLogoUpdate"
    />
    <frame-panel
      :outer-frame.sync="outerFrame"
      :frame-label.sync="frameLabel"
      :label-font.sync="labelFont"
      :label-text-size.sync="labelTextSize"
      :custom-frame-color.sync="customFrameColor"
      :frame-color.sync="frameColor"
      :frames="frames"
      :fonts="fonts"
    />
    <options-panel
      :size.sync="size"
      :level.sync="level"
      :size-options="sizeOptions"
      :level-options="levelOptions"
    />
  </v-expansion-panels>
</template>

<script>
import ColorsPanel from "./ColorsPanel.vue";
import DesignPanel from "./DesignPanel.vue";
import LogoPanel from "./LogoPanel.vue";
import FramePanel from "./FramePanel.vue";
import OptionsPanel from "./OptionsPanel.vue";


export default {
  name: "QRCodeCustomizer",
  components: {
    ColorsPanel,
    DesignPanel,
    LogoPanel,
    FramePanel,
    OptionsPanel,
  },
  data() {
    return {
      activePanel: null,
      accordion: true,
      backcolor: "#FFFFFF",
      frontcolor: "#000000",
      gradientColor: "#8900D5",
      transparent: false,
      transparentCode: false,
      negativeQr: false,
      gradient: false,
      radial: false,
      pattern: "default",
      markerOut: "default",
      markerIn: "default",
      markersColor: false,
      markerOutColor: "#000000",
      markerInColor: "#000000",
      differentMarkersColor: false,
      markerTopRightOutline: "#000000",
      markerTopRightCenter: "#000000",
      markerBottomLeftOutline: "#000000",
      markerBottomLeftCenter: "#000000",
      optionLogo: "none",
      logoFile: null,
      logoError: "",
      noLogoBg: false,
      logoSize: 100,
      bgImage: null,
      imageZoom: 0.5,
      customWatermark: null,
      outerFrame: "none",
      frameLabel: this.$t("scan_me"),
      labelFont: "",
      labelTextSize: 100,
      customFrameColor: false,
      frameColor: "#000000",
      size: 8,
      level: "L",
      patterns: {
        default: { preview: "" },
      },
      markers: {
        default: { path: "M0,0v14h14V0H0z M12,12H2V2h10V12z" },
      },
      markersIn: {
        default: { path: "M0,0h6v6H0z", marker: true },
      },
      frames: {
        none: { path: "", label_size: null, label_offset: null },
      },
      watermarks: [
      ],
      fonts: [],
      sizeOptions: [
        { text: "200", value: 8 },
        { text: "400", value: 12 },
        { text: "600", value: 16 },
        { text: "800", value: 20 },
        { text: "1000", value: 24 },
        { text: "1200", value: 28 },
        { text: "1400", value: 32 },
      ],
      levelOptions: [
        { text: this.$t("precision_l"), value: "L" },
        { text: this.$t("precision_m"), value: "M" },
        { text: this.$t("precision_q"), value: "Q" },
        { text: this.$t("precision_h"), value: "H" },
      ],
      uploader: true,
    };
  },
  mounted() {
    this.loadFonts();
  },
  methods: {
    handleImageUpdate(file) {
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.bgImage = e.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        this.bgImage = null;
      }
    },
    handleLogoUpdate(file) {
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.customWatermark = e.target.result;
          this.optionLogo = "custom";
          this.logoError = "";
        };
        reader.onerror = () => {
          this.logoError = this.$t("invalid_image");
        };
        reader.readAsDataURL(file);
      }
    },
    loadFonts() {
      this.fonts = [
        { text: "Roboto", value: "Roboto.ttf" },
        { text: "Arial", value: "Arial.ttf" },
      ];
    },
  },
};
</script>

<style scoped>
.ltr {
  direction: ltr;
}
</style>
