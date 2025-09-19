<template>
  <v-row no-gutters>
    <v-col class="d-flex flex-wrap mb-4" cols="12">
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

    <v-col class="pa-2" cols="12" md="6">
      <v-switch
        v-model="bgSwitch"
        class="ma-0"
        :label="$t('gradient')"
        color="#403199"
      />
    </v-col>

    <template v-if="bgSwitch">
      <v-col class="pa-2" cols="12" md="6">
        <v-radio-group
          v-model="options.dotsOptions.gradient.type"
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

      <v-col class="pa-2 mb-2 d-flex justify-space-around" cols="12">
        <v-card
          v-for="(color, i) in colors"
          :key="i"
          :style="{
            backgroundImage: `linear-gradient(to bottom right, ${color[0]}, ${color[1]})`,
          }"
          class="rounded-circle colors-circle cursor-pointer overflow-hidden"
          flat
          height="48"
          width="48"
          @click="setRecommends(color)"
        />
      </v-col>

      <v-col class="pa-2" cols="12" md="6">
        <color-picker
          v-model="options.dotsOptions.gradient.colorStops[0].color"
          :label="$t('first_color')"
          @input="$emit('update', true)"
        />
      </v-col>

      <v-col class="pa-2" cols="12" md="6">
        <color-picker
          v-model="options.dotsOptions.gradient.colorStops[1].color"
          :label="$t('second_color')"
          @input="$emit('update', true)"
        />
      </v-col>
    </template>

    <v-col v-else class="pa-2" cols="12" md="6">
      <color-picker
        v-model="options.dotsOptions.color"
        :label="$t('dot_colors')"
        @input="$emit('update', true)"
      />
    </v-col>
  </v-row>
</template>

<script>
import ColorPicker from "./colorPicker";

export default {
  name: "qrCodeDots",

  components: { ColorPicker },

  props: {
    value: Object,
    colors: Array,
  },

  data: () => ({
    bgSwitch: false,
    designObject: [
      { value: "square", img: "/media/designSvg/dot.svg" },
      { value: "rounded", img: "/media/designSvg/special-diamond.svg" },
      { value: "extra-rounded", img: "/media/designSvg/special-circle.svg" },
      { value: "dots", img: "/media/designSvg/circle.svg" },
      { value: "classy", img: "/media/designSvg/ellipse.svg" },
      { value: "classy-rounded", img: "/media/designSvg/oriental.svg" },
    ],
    rotations: 0,
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
        return this.rotations;
      },
      set(val) {
        this.rotations = val;
        this.options.dotsOptions.gradient.rotation = (val * Math.PI) / 180;
        this.$emit("update", true);
      },
    },
  },

  watch: {
    bgSwitch(val) {
      if (!val) this.options.dotsOptions.gradient = null;
      else {
        this.options.dotsOptions.gradient = {
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
      this.options.dotsOptions.type = value;
      this.$emit("update");
    },
    setRecommends(color) {
      this.computedRotation = Math.floor(Math.random() * 360);
      this.options.cornersSquareOptions.color = color[0];
      this.options.cornersDotOptions.color = color[1];
      this.options.dotsOptions.gradient.colorStops[0].color = color[0];
      this.options.dotsOptions.gradient.colorStops[1].color = color[1];
      this.$emit("update", true);
    },
  },
};
</script>

<style scoped>
.colors-circle::after {
  content: "";
  border: 2px solid #fff;
  border-radius: 50%;
  width: 90%;
  height: 90%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
