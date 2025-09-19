<template>
  <v-row no-gutters>
    <v-col class="pa-2" cols="12">
      <v-slider
        v-model="computedValue.qrOptions.typeNumber"
        append-icon="mdi-numeric"
        :label="$t('qr_precision')"
        max="32"
        min="0"
        thumb-label
        thumb-color="#403199"
        color="#403199"
        track-color="grey"
        @input="$emit('update', true)"
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
            {{ value }}
          </div>
        </template>
      </v-slider>
    </v-col>

    <v-col class="pa-2" cols="12" md="6">
      <v-autocomplete
        v-model="computedValue.qrOptions.mode"
        :items="modeItems"
        dense
        hide-details
        :label="$t('mode')"
        class="contentinfo"
        outlined
        @input="$emit('update', true)"
      />
    </v-col>

    <v-col class="pa-2" cols="12" md="6">
      <v-autocomplete
        v-model="computedValue.qrOptions.errorCorrectionLevel"
        :items="correctionItems"
        dense
        hide-details
        :label="$t('error_correction_level')"
        class="contentinfo"
        outlined
        @input="$emit('update', true)"
      />
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "qrCodeOptions",

  props: {
    value: Object,
  },

  data: () => ({
    correctionItems: ["L", "M", "Q", "H"],
  }),

  computed: {
    modeItems() {
      return [
        { text: this.$t('numeric'), value: "Numeric" },
        { text: this.$t('alphanumeric'), value: "Alphanumeric" },
        { text: this.$t('byte'), value: "Byte" },
        { text: "Kanji", value: "Kanji" },
      ];
    },
    computedValue: {
      get() {
        return this.value;
      },
      set(val) {
        this.$emit("input", val);
      },
    },
  },
};
</script>

<style scoped></style>
