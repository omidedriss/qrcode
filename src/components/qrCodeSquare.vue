<template>
  <v-row no-gutters>
    <v-col class="d-flex flex-wrap mb-4" cols="12">
      <div>
        <v-hover v-slot="{ hover }">
          <v-card
            :elevation="hover ? 4 : 8"
            class="ma-2 pa-3 rounded-lg d-flex align-center justify-center"
            height="60"
            width="60"
            @click="chooseDesign('')"
          >
            <v-icon color="#222" size="32">mdi-cancel</v-icon>
          </v-card>
        </v-hover>
      </div>
      <div v-for="(item, index) in designObject" :key="index">
        <v-hover v-slot="{ hover }">
          <v-card
            :elevation="hover ? 4 : 8"
            class="ma-2 pa-3 rounded-lg d-flex align-center justify-center"
            height="60"
            width="60"
            @click="chooseDesign(item.value)"
          >
            <img :alt="item.value" :src="item.img" class="rounded-0" />
          </v-card>
        </v-hover>
      </div>
    </v-col>

    <v-col class="d-flex align-center grey--text" cols="12">
      {{ $t('dot_colors') }}
      <v-divider class="mx-2" />
    </v-col>

    <v-col cols="12" md="6">
      <v-switch v-model="bgSwitch" :label="$t('color_spectrum')" color="#403199" />
    </v-col>

    <template v-if="bgSwitch">
      <v-col class="pa-2" cols="12" md="6">
        <v-radio-group
          v-model="options.cornersSquareOptions.gradient.type"
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
          v-model="options.cornersSquareOptions.gradient.colorStops[0].color"
          :label="$t('first_color')"
          @input="$emit('update', true)"
        />
      </v-col>

      <v-col class="pa-2" cols="12" md="6">
        <color-picker
          v-model="options.cornersSquareOptions.gradient.colorStops[1].color"
          :label="$t('second_color')"
          @input="$emit('update', true)"
        />
      </v-col>
    </template>

    <v-col v-else class="pa-2" cols="12" md="6">
      <color-picker
        v-model="options.cornersSquareOptions.color"
        :label="$t('dot_colors')"
        @input="$emit('update', true)"
      />
    </v-col>
  </v-row>
</template>

<script>
import ColorPicker from "./colorPicker";

export default {
  name: "qrCodeSquare",

  components: { ColorPicker },

  props: {
    value: Object,
    color: Array,
  },

  data: () => ({
    helpData: [],
    serviceName: "Qrland",
    componentName: "qrCodeSquare",

    bgSwitch: false,
    designObject: [
      { value: "square", img: "/media/markOut/default.svg" },
      { value: "dot", img: "/media/markOut/circle.svg" },
      { value: "extra-rounded", img: "/media/markOut/rounded.svg" },
    ],
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
        this.options.cornersSquareOptions.gradient.rotation =
          (val * Math.PI) / 180;
        this.$emit("update", true);
      },
    },
  },

  watch: {
    bgSwitch(val) {
      if (!val) this.options.cornersSquareOptions.gradient = null;
      else {
        this.options.cornersSquareOptions.gradient = {
          type: "linear",
          rotation: 0,
          colorStops: [
            {
              offset: 0,
              color: "#001554",
            },
            {
              offset: 1,
              color: "#001554",
            },
          ],
        };
      }
      this.$emit("update", true);
    },
  },

  methods: {
    chooseDesign(value) {
      this.options.cornersSquareOptions.type = value;
      this.$emit("update");
    },
  },
};
</script>

<style scoped></style>
