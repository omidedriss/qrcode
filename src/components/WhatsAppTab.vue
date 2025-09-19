<template>
  <v-container class="whatsapp-tab fadeIn" v-if="isActive" id="whatsapp">
    <p class="rtl-label tag">{{ $t("buttons.whatsapp") }}</p>

    <v-form ref="whatsappTab" v-model="valid" lazy-validation>
      <!-- LTR Layout (English, Turkish, Russian) -->
      <v-row v-if="!isRTL">
        <v-col cols="12" md="5" class="pa-0 px-4 width-100">
          <v-select
            v-model="form.wapp_countrycode"
            :items="countries"
            :rules="rulesCountry"
            :label="$t('country_code')"
            item-text="name"
            item-value="code"
            required
            outlined
            dense
            class="country-code phone-field w100"
            ><template v-slot:selection="{ item }">{{ item.code }}</template>
            <template v-slot:item="{ item }"
              >{{ item.code }} ({{ item.name }})</template
            ></v-select
          >
        </v-col>
        <v-col cols="12" md="7" class="pa-0 pr-3">
          <v-text-field
            v-model="form.wapp_number"
            :label="$t('phone_number')"
            type="number"
            required
            outlined
            :rules="rules"
            clear-icon="mdi-close-circle-outline"
            counter="10"
            dense
            class="phone-field"
            :hint="$t('phone_hint')"
            maxLength="10"
            :placeholder="$t('phone_hint')"
            append-icon="fa fa-phone"
            clearable
          ></v-text-field>
        </v-col>
      </v-row>

      <!-- RTL Layout (Persian, Arabic) -->
      <v-row v-else>
        <v-col cols="12" md="7" class="pa-0 pr-4">
          <v-text-field
            v-model="form.wapp_number"
            :label="$t('phone_number')"
            type="number"
            required
            outlined
            :rules="rules"
            clear-icon="mdi-close-circle-outline"
            counter="10"
            dense
            class="phone-field"
            :hint="$t('phone_hint')"
            maxLength="10"
            :placeholder="$t('phone_hint')"
            append-icon="fa fa-phone"
            clearable
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="5" class="pa-0 px-3 w100">
          <v-select
            v-model="form.wapp_countrycode"
            :items="countries"
            :rules="rulesCountry"
            :label="$t('country_code')"
            item-text="name"
            item-value="code"
            required
            outlined
            dense
            class="country-code phone-field w100"
            ><template v-slot:selection="{ item }">{{ item.code }}</template>
            <template v-slot:item="{ item }"
              >{{ item.code }} ({{ item.name }})</template
            ></v-select
          >
        </v-col>
      </v-row>

      <v-col cols="12" md="12" class="pa-2">
        <v-textarea
          v-model="form.wapp_message"
          :label="$t('message')"
          rows="3"
          :counter="255"
          :rules="[(v) => v.length <= 255 || $t('max_255_chars')]"
          outlined
        ></v-textarea>
      </v-col>
    </v-form>
    <!-- </v-card> -->
  </v-container>
</template>

<script>
import { countries } from "./countryCodes.js";

export default {
  name: "WhatsAppTab",
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
        wapp_countrycode: "",
        wapp_number: "",
        wapp_message: "",
      },
      countries,
      rules: [
        (v) => !!v || this.$t("field_required"),
        (v) => /^\d+$/.test(v) || this.$t("invalid_phone"),
        (v) => v.length === 10 || this.$t("invalid_phone"),
      ],
      rulesCountry: [(v) => !!v || this.$t("field_required")],
    };
  },
  computed: {
    isActive() {
      return this.getsection === "#whatsapp";
    },
    isRTL() {
      return this.$i18n.locale === "fa" || this.$i18n.locale === "ar";
    },
  },
  methods: {
    validate() {
      return this.$refs.whatsappForm.validate();
    },
  },
};
</script>

<style scoped>
.v-card {
  padding: 16px;
}
.w100 {
  width: 100% !important;
  max-width: 100% !important;
}
</style>
