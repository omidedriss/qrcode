<template>
  <div>
    <v-card :max-width="size" flat>
      <v-text-field
        v-model="computedValue"
        :color="color"
        class="pa-0 rounded-lg contentinfo"
        dense
        hide-details
        outlined
        v-bind="$attrs"
      >
        <template v-slot:append>
          <div
            :style="{ background: value }"
            class="d-flex align-center cursor-pointer pa-3 ma-2 mt-0 rounded color-pointer"
            @click="showColor = !showColor"
          ></div>
        </template>
      </v-text-field>
    </v-card>

    <v-expand-transition>
      <v-color-picker
        v-if="showColor"
        v-model="computedValue"
        :draggable="true"
        class="mt-1"
        dense
        dot-size="18"
        label="$t('colors')"
        mode="hexa"
        outlined
        required
      />
    </v-expand-transition>
  </div>
</template>

<script>
export default {
  name: "colorPicker",
  props: {
    value: String,
    size: {
      type: [String, Number],
      default: 200,
    },
    color: {
      type: String,
      default: "",
    },
    show: {
      type: Boolean,
      default: true,
    },
  },

  data() {
    return {
      showColor: false,
    };
  },

  computed: {
    computedValue: {
      get() {
        return this.fixColor(this.value);
      },
      set(val) {
        return this.$emit("input", this.fixColor(val));
      },
    },
  },

  methods: {
    fixColor(val) {
      let str = val.split("#");
      let color = str.length > 1 ? str[1] : str[0];
      color = color.length > 6 ? color : color + "FF";
      return "#" + color;
    },
  },
};
</script>

<style scoped>
.v-input >>> .v-input__slot {
  padding-left: 0 !important;
}

.color-pointer {
  border: thin solid rgba(0, 0, 0, 0.2);
}
</style>
