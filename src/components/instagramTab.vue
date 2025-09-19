<template>
  <v-container
    class="instagram-tab fadeIn"
    v-if="getsection === '#instagram'"
    id="instagram"
  >
    <p class="rtl-label tag">{{ $t("buttons.instagram") }}</p>

    <v-form ref="instagramTab" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12" md="5" class="pa-0 px-3" :order="isRTL ? '2' : '1'">
          <v-select
            v-model="form.insta_countrycode"
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
        <v-col cols="12" md="7" class="pa-0 pr-3" :order="isRTL ? '1' : '2'">
          <v-text-field
            v-model="form.insta_number"
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
        <v-col cols="12" md="12" class="pa-2" order="3">
          <v-textarea
            v-model="form.insta_message"
            :label="$t('message')"
            rows="3"
            :counter="255"
            :rules="[(v) => v.length <= 255 || $t('max_255_chars')]"
            outlined
          ></v-textarea>
        </v-col>
      </v-row>
    </v-form>
    <!-- </v-card> -->
  </v-container>
</template>

<script>
import { countries } from "./countryCodes.js";

export default {
  name: "instagramTab",
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
        insta_countrycode: "",
        insta_number: "",
        insta_message: "",
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
      return this.getsection === "#instagram" ? "show active" : "";
    },
    isRTL() {
      return this.$i18n.locale === "fa" || this.$i18n.locale === "ar";
    },
  },
  methods: {
    validate() {
      return this.$refs.instagramForm.validate();
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
