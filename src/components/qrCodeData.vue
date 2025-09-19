<template>
  <v-row no-gutters>
    <v-col class="pa-2" cols="12" md="6">
      <v-text-field
        v-model="options.data"
        :readonly="readonly"
        append-icon="mdi-link-variant-plus"
        dense
        :label="$t('link')"
        outlined
        class="contentinfo"
        placeholder="qr2.in"
        @input="$emit('update')"
      />
    </v-col>

    <v-col class="pa-2" col="12" md="6">
      <v-text-field
        v-model="options.margin"
        dense
        :label="$t('margin')"
        min="0"
        outlined
        type="number"
        @input="updateSizes($event, 'margin')"
        class="contentinfo"
      />
    </v-col>

    <v-col class="pa-2" col="12" md="6">
      <v-text-field
        v-model="options.width"
        dense
        :label="$t('width')"
        min="0"
        outlined
        type="number"
        @input="updateSizes($event, 'width')"
        class="contentinfo"
      />
    </v-col>

    <v-col class="pa-2" col="12" md="6">
      <v-text-field
        v-model="options.height"
        dense
        :label="$t('height')"
        min="0"
        outlined
        type="number"
        @input="updateSizes($event, 'height')"
        class="contentinfo"
      />
    </v-col>

    <v-col class="d-flex align-center grey--text" cols="12">
      {{ $t('background') }}
      <v-divider class="mx-2" />
    </v-col>

    <v-col cols="12" md="6">
      <v-switch v-model="bgSwitch" :label="$t('gradient')" color="#403199" />
    </v-col>

    <template v-if="bgSwitch">
      <v-col class="pa-2" cols="12" md="6">
        <v-radio-group
          v-model="options.backgroundOptions.gradient.type"
          class="ma-0"
          row
          @change="$emit('update', true)"
        >
          <v-radio :label="$t('linear')" value="linear" />
          <v-radio :label="$t('radial')" value="radial" />
        </v-radio-group>
      </v-col>

      <v-col class="pa-2" cols="12">
        <v-slider
          v-model="computedRotation"
          append-icon="mdi-rotate-360"
          :label="$t('color_rotation')"
          max="360"
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
              {{ value }}°
            </div>
          </template>
        </v-slider>
      </v-col>

      <v-col class="pa-2" cols="12" md="6">
        <color-picker
          v-model="options.backgroundOptions.gradient.colorStops[0].color"
          :label="$t('first_color')"
          @input="$emit('update', true)"
        />
      </v-col>

      <v-col class="pa-2" cols="12" md="6">
        <color-picker
          v-model="options.backgroundOptions.gradient.colorStops[1].color"
          :label="$t('second_color')"
          @input="$emit('update', true)"
        />
      </v-col>
    </template>

    <v-col v-else class="pa-2" cols="12" md="6">
      <color-picker
        v-model="options.backgroundOptions.color"
        :label="$t('background')"
        @input="$emit('update', true)"
      />
    </v-col>
  </v-row>
</template>

<script>
import ColorPicker from "./colorPicker";

export default {
  name: "qrCodeData",

  components: { ColorPicker },

  props: {
    value: Object,
    readonly: {
      type: Boolean,
      default: true,
    },
  },

  data: () => ({
    bgSwitch: false,
    rotation: 0,
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
    computedRotation: {
      get() {
        return this.rotation;
      },
      set(val) {
        this.rotation = val;
        this.options.backgroundOptions.gradient.rotation =
          (val * Math.PI) / 180;
        this.$emit("update", true);
      },
    },
  },

  watch: {
    bgSwitch(val) {
      if (!val) this.options.backgroundOptions.gradient = null;
      else {
        this.options.backgroundOptions.gradient = {
          type: "linear",
          rotation: 0,
          colorStops: [
            {
              offset: 0,
              color: "#ffffffff",
            },
            {
              offset: 1,
              color: "#ffffffff",
            },
          ],
        };
      }
      this.$emit("update", true);
    },
  },

  methods: {
    updateSizes(e, field) {
      if (e < 0) this.options[field] = 0;
      this.$emit("update");
    },
  },
};
</script>

<style scoped></style>
