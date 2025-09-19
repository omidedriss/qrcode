<template>
  <v-container class="zoom-tab fadeIn" v-if="getsection === '#zoom'" id="zoom">
    <p class="rtl-label tag">{{ $t("buttons.zoom") }}</p>
    <v-form ref="zoomTab" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12" sm="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.zoom_id"
            :label="$t('reunion_id')"
            :rules="[(v) => !!v || $t('field_required')]"
            required
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" sm="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.zoom_pwd"
            :label="$t('password')"
            :rules="[(v) => !!v || $t('field_required')]"
            :type="showPassword ? 'text' : 'password'"
            :append-icon="showPassword ? 'fa fa-eye' : 'fa fa-eye-slash'"
            @click:append="showPassword = !showPassword"
            required
            outlined
            dense
          ></v-text-field>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>

<script>
export default {
  name: "ZoomTab",
  props: {
    getsection: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      valid: true,
      form: {
        zoom_id: "",
        zoom_pwd: "",
      },
      showPassword: false,
    };
  },
  computed: {
    isActive() {
      return this.getsection === "#zoom" ? "show active" : "";
    },
  },
  methods: {
    validate() {
      return this.$refs.zoomTab.validate();
    },
  },
};
</script>

<style scoped></style>
