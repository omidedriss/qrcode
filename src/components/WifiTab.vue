<template>
  <v-container class="wifi-tab fadeIn" v-if="getsection === '#wifi'" id="wifi">
    <!-- <v-card class="pa-4"> -->
    <p class="rtl-label tag">{{ $t("buttons.wifi") }}</p>
    <v-form ref="wifiTab" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.ssid"
            :label="$t('network_name')"
            :rules="[(v) => !!v || $t('field_required')]"
            placeholder="SSID"
            required
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-select
            v-model="form.networktype"
            :items="networkTypes"
            :label="$t('network_type')"
            outlined
            dense
          ></v-select>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-checkbox
            v-model="form.wifihidden"
            :label="$t('hidden')"
            color="#403199"
            dense
            class="dir"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.wifipass"
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
    <!-- </v-card> -->
  </v-container>
</template>

<script>
export default {
  name: "WifiTab",
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
        ssid: "",
        networktype: "",
        wifipass: "",
        wifihidden: false,
      },
      showPassword: false,
      networkTypes: [
        { text: "WEP", value: "WEP" },
        { text: "WPA/WPA2", value: "WPA" },
        { text: this.$t("no_encryption"), value: "" },
      ],
    };
  },
  computed: {
    isActive() {
      return this.getsection === "#wifi" ? "show active" : "";
    },
  },
  methods: {
    validate() {
      return this.$refs.wifiForm.validate();
    },
  },
};
</script>

<style scoped>
.dir {
  direction: rtl;
}
</style>
