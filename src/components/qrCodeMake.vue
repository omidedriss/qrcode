<template>
  <v-expansion-panels class="px-4" flat>
    <v-expansion-panel v-for="(item, i) in panels" :key="i" class="mb-4">
      <v-expansion-panel-header class="text-right">
        <div
          class="d-flex row justify-start algin-center"
          style="align-items: center !important"
        >
          <v-icon class="me-2">mdi-{{ item.icon }}</v-icon>
          {{ item.title }}
        </div>
      </v-expansion-panel-header>

      <v-expansion-panel-content class="py-5">
        <component
          :is="item.name"
          v-model="options"
          :colors="colors"
          @update="updateStyle"
        />
      </v-expansion-panel-content>
    </v-expansion-panel>
  </v-expansion-panels>
</template>

<script>
import QRCodeStyling from "qr-code-styling";
import { debounce } from "@/main";
import QrCodeData from "@/view/pages/qrland/qrCodeStyle/components/qrCodeData";
import QrCodeLogo from "@/view/pages/qrland/qrCodeStyle/components/qrCodeLogo";
import QrCodeDots from "@/view/pages/qrland/qrCodeStyle/components/qrCodeDots";
import QrCodeEye from "@/view/pages/qrland/qrCodeStyle/components/qrCodeEye";
import QrCodeOptions from "@/view/pages/qrland/qrCodeStyle/components/qrCodeOptions";
import QrCodeSquare from "./components/qrCodeSquare";

let qrCode = null;

export default {
  name: "qrCodeMake",

  components: {
    QrCodeOptions,
    QrCodeSquare,
    QrCodeEye,
    QrCodeDots,
    QrCodeData,
    QrCodeLogo,
  },

  props: {
    value: Object,
    canvasId: String,
    file: String,
  },

  data: () => ({
    helpData: [],
    serviceName: "Qrland",
    componentName: "qrCodeMake",
    options: {
      width: 300,
      height: 300,
      data: "qr2.in",
      margin: 5,
      qrOptions: {
        typeNumber: 2,
        mode: "Byte",
        errorCorrectionLevel: "H",
      },
      imageOptions: {
        hideBackgroundDots: true,
        imageSize: 0.4,
        margin: 0,
      },
      dotsOptions: {
        type: "extra-rounded",
        color: "#6a1a4c",
        gradient: {
          type: "linear",
          rotation: 0,
          colorStops: [
            { offset: 0, color: "#933490" },
            { offset: 1, color: "#933490" },
          ],
        },
      },
      backgroundOptions: { color: "#FFFFFFFF" },
      image: null,
      dotsOptionsHelper: {
        colorType: { single: true, gradient: false },
        gradient: {
          linear: true,
          radial: false,
          color1: "#6a1a4c",
          color2: "#6a1a4c",
          rotation: "0",
        },
      },
      cornersSquareOptions: {
        type: "extra-rounded",
        color: "#690a70",
      },
      cornersSquareOptionsHelper: {
        colorType: { single: true, gradient: false },
        gradient: {
          linear: true,
          radial: false,
          color1: "#000000",
          color2: "#000000",
          rotation: "0",
        },
      },
      cornersDotOptions: { type: "", color: "#b00cab" },
      cornersDotOptionsHelper: {
        colorType: { single: true, gradient: false },
        gradient: {
          linear: true,
          radial: false,
          color1: "#000000",
          color2: "#000000",
          rotation: "0",
        },
      },
      backgroundOptionsHelper: {
        colorType: { single: true, gradient: false },
        gradient: {
          linear: true,
          radial: false,
          color1: "#ffffff",
          color2: "#ffffff",
          rotation: "0",
        },
      },
    },
    panels: [
      { name: "QrCodeData", title: this.$t("qr_color_size"), icon: "qrcode-edit" },
      { name: "QrCodeDots", title: this.$t("dots_pattern_color"), icon: "dots-triangle" },
      { name: "QrCodeEye", title: this.$t("eye_pattern_color"), icon: "bullseye" },
      { name: "QrCodeSquare", title: this.$t("square_pattern_color"), icon: "square" },
      { name: "QrCodeFrame", title: this.$t("frame_pattern_color"), icon: "border-all" },
      { name: "QrCodeLogo", title: this.$t("logo_settings"), icon: "image" },
      { name: "QrCodeOptions", title: this.$t("qr_settings"), icon: "cogs" },
    ],
    colors: [
      ["#649173", "#DBD5A4"],
      ["#B993D6", "#8CA6DB"],
      ["#870000", "#190A05"],
      ["#00d2ff", "#3a7bd5"],
      ["#DAD299", "#B0DAB9"],
      ["#E6DADA", "#274046"],
      ["#50C9C3", "#96DEDA"],
      ["#4b6cb7", "#182848"],
      ["#EC6F66", "#F3A183"],
      ["#5f2c82", "#49a09d"],
    ],
  }),

  async mounted() {
    qrCode = new QRCodeStyling(this.computedValue);
    if (!!this.value && !!Object.keys(this.value).length) {
      let keys = Object.keys(this.value);
      if (
        keys.includes("imageOptions") &&
        keys.includes("qrOptions") &&
        keys.includes("dotsOptions") &&
        keys.includes("backgroundOptions") &&
        keys.includes("cornersSquareOptions") &&
        keys.includes("cornersDotOptions")
      ) {
        this.options = this.value;
      } else {
        await this.ranColor();
        this.updateStyle();
        this.$emit("save", this.options);
      }
    } else {
      await this.ranColor();
      this.updateStyle();
      this.$emit("save", this.options);
    }
  },

  computed: {
    computedValue: {
      get() {
        return this.value;
      },
      set(val) {
        this.$emit("input", val);
      },
    },
  },

  methods: {
    appendCanvas(url) {
      this.options.data = url;
      qrCode.append(document.getElementById(this.canvasId ?? "canvas-wrapper"));
      this.updateStyle();
    },
    updateStyle(timer = false, options = this.options) {
      this.$nextTick(() => {
        debounce(
          () => {
            try {
              qrCode.update(options);
            } catch (e) {
              console.log(e);
            }
            this.getDataUrl();
          },
          !!timer && timer !== null ? 500 : 0
        );
      });
    },
    async getDataUrl(type = "svg") {
      let blob = await qrCode.getRawData(type);
      const reader = new FileReader();
      reader.addEventListener("loadend", () => {
        this.$emit("update:file", reader.result);
      });
      reader.readAsDataURL(blob);
    },
    ranColor() {
      return new Promise((resolve) => {
        let rand = this.colors[Math.floor(Math.random() * 10)];
        this.options.dotsOptions.gradient.colorStops = [
          { offset: 0, color: rand[0] },
          { offset: 1, color: rand[1] },
        ];
        resolve("resolve");
      });
    },
  },
};
</script>

<style scoped>
#canvas-wrapper >>> canvas {
  max-width: 100%;
  max-height: 300px;
}

.v-expansion-panel {
  border-radius: 15px !important;
  box-shadow: 8px 5px 10px #f49d1a;
}

.v-expansion-panel
  >>> .v-expansion-panel-header.v-expansion-panel-header--active {
  background-color: #001554 !important;
  border-radius: 15px !important;
  color: whitesmoke !important;
}

.v-expansion-panel
  >>> .v-expansion-panel-header.v-expansion-panel-header--active
  .v-icon {
  color: whitesmoke !important;
}
</style>
