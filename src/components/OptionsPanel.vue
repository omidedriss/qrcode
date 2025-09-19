<template>
  <div>
    <p class="rtl-label tag">{{ $t("option_selection") }}</p>
    <v-row>
      <v-col cols="12" sm="6">
        <v-select
          v-model="localSize"
          :items="sizeOptions"
          :label="$t('size')"
          outlined
          dense
        ></v-select>
      </v-col>
      <v-col cols="12" sm="6">
        <v-select
          v-model="localLevel"
          :items="levelOptions"
          :label="$t('error_correction_level')"
          outlined
          dense
        ></v-select>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  name: "OptionsPanel",
  props: {
    size: Number,
    level: String,
    sizeOptions: Array,
    levelOptions: Array,
  },
  computed: {
    localSize: {
      get() {
        return this.size;
      },
      set(value) {
        this.$emit("update:size", value);
      },
    },
    localLevel: {
      get() {
        return this.level;
      },
      set(value) {
        this.$emit("update:level", value);
      },
    },
  },
};
</script>

<style scoped></style>
