<template>
  <v-row no-gutters>
    <v-col class="pa-2" cols="12" md="6">
      <v-file-input
        clearable
        dense
        :label="$t('upload_logo')"
        outlined
        prepend-icon=""
        prepend-inner-icon="mdi-camera"
        @change="getImg($event)"
        class="contentinfo"
      />
    </v-col>

    <v-col class="pa-2" cols="12" md="6">
      <v-checkbox
        v-model="options.imageOptions.hideBackgroundDots"
        :disabled="!options.image"
        class="ma-0"
        :label="$t('hide_dots_behind_logo')"
        @change="$emit('update')"
      />
    </v-col>

    <v-col class="pa-2" cols="12" md="6">
      <v-slider
        v-model="computedImgSize"
        :disabled="!options.image"
        append-icon="mdi-image-size-select-large"
        :label="$t('logo_size')"
        max="10"
        min="0"
        step="1"
        thumb-label
        track-color="grey"
      >
        <template v-slot:thumb-label="{ value }">
          <div
            class="d-flex align-center justify-center"
            style="
              width: 20px;
              height: 20px;
              background-color: rgba(12, 15, 26, 0.7);
              border-radius: 50%;
            "
          >
            {{ value / 10 }}
          </div>
        </template>
      </v-slider>
    </v-col>

    <v-col class="pa-2" cols="12" md="6">
      <v-text-field
        v-model="options.imageOptions.margin"
        :disabled="!options.image"
        dense
        :label="$t('logo_margin')"
        min="0"
        outlined
        class="contentinfo"
        type="number"
        @input="$emit('update')"
      />
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "qrCodeLogo",

  props: {
    value: Object,
  },

  data: () => ({
    imgSize: 5,
  }),

  computed: {
    options: {
      get() {
        return this.value;
      },
      set(val) {
        this.$emit("input", val);
      },
    },
    computedImgSize: {
      get() {
        return this.imgSize;
      },
      set(val) {
        this.imgSize = val;
        this.options.imageOptions.imageSize = val / 10;
        this.$emit("update", true);
      },
    },
  },

  methods: {
    getImg(e) {
      if (!!e && e !== null) {
        const reader = new FileReader();
        reader.readAsDataURL(e);
        reader.onload = () => {
          this.options.image = reader.result;
        };
      } else {
        this.options.image = "";
      }
      this.$emit("update", true);
    },
  },
};
</script>

<style scoped></style>
