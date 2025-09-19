<template>
  <div class="preview-panel">
    <v-subheader>Preview Customization</v-subheader>
    <v-slider
      v-model="localBorderRadius"
      label="Border Radius"
      min="0"
      max="20"
      thumb-label
      thumb-color="#403199"
      color="#403199"
    ></v-slider>
    <v-slider
      v-model="localShadowIntensity"
      label="Shadow Intensity"
      min="0"
      max="10"
      thumb-label
      thumb-color="#403199"
      color="#403199"
    ></v-slider>
    <v-color-picker
      v-model="localBorderColor"
      flat
      mode="hexa"
      label="Border Color"
      class="ltr"
    ></v-color-picker>
  </div>
</template>

<script>
export default {
  name: "PreviewPanel",
  props: {
    borderRadius: Number,
    shadowIntensity: Number,
    borderColor: String,
  },
  computed: {
    localBorderRadius: {
      get() {
        return this.borderRadius;
      },
      set(value) {
        this.$emit("update:borderRadius", value);
      },
    },
    localShadowIntensity: {
      get() {
        return this.shadowIntensity;
      },
      set(value) {
        this.$emit("update:shadowIntensity", value);
      },
    },
    localBorderColor: {
      get() {
        return this.borderColor;
      },
      set(value) {
        this.$emit("update:borderColor", value);
      },
    },
  },
};
</script>

<style scoped>
.preview-panel {
  padding: 10px;
}
.ltr {
  direction: ltr;
}
</style>
