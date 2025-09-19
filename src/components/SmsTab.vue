<template>
  <v-container class="sms-tab fadeIn" v-if="isActive" id="sms">
    <p class="rtl-label tag">{{ $t("buttons.sms") }}</p>
    <v-form ref="smsTab" v-model="valid" lazy-validation>
      <!-- LTR Layout (English, Turkish, Russian) -->
      <v-row v-if="!isRTL">
        <v-col cols="12" md="4" class="pa-0 px-3 pt-5">
          <v-select
            v-model="form.countrycodesms"
            :items="countries"
            :label="$t('country_code')"
            :rules="[(v) => !!v || $t('field_required')]"
            item-text="name"
            item-value="code"
            required
            outlined
            dense
            class="country-code phone-field"
            ><template v-slot:selection="{ item }">{{ item.code }}</template>
            <template v-slot:item="{ item }"
              >{{ item.code }} ({{ item.name }})</template
            ></v-select
          >
        </v-col>
        <v-col cols="12" md="7" class="pa-0 pt-5">
          <v-text-field
            class="phone-field"
            v-model="form.sms"
            :label="$t('phone_number')"
            :rules="[
              (v) => !!v || $t('field_required'),
              (v) => /^\d+$/.test(v) || $t('invalid_phone'),
              (v) => v.length === 10 || $t('invalid_phone'),
            ]"
            type="number"
            required
            outlined
            clear-icon="mdi-close-circle-outline"
            counter="10"
            dense
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
        <v-col cols="12" md="7" class="pa-0 px-3 pt-5">
          <v-text-field
            class="phone-field"
            v-model="form.sms"
            :label="$t('phone_number')"
            :rules="[
              (v) => !!v || $t('field_required'),
              (v) => /^\d+$/.test(v) || $t('invalid_phone'),
              (v) => v.length === 10 || $t('invalid_phone'),
            ]"
            type="number"
            required
            outlined
            clear-icon="mdi-close-circle-outline"
            counter="10"
            dense
            :hint="$t('phone_hint')"
            maxLength="10"
            :placeholder="$t('phone_hint')"
            append-icon="fa fa-phone"
            clearable
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="5" class="pa-0 px-3 pt-5">
          <v-select
            v-model="form.countrycodesms"
            :items="countries"
            :label="$t('country_code')"
            :rules="[(v) => !!v || $t('field_required')]"
            item-text="name"
            item-value="code"
            required
            outlined
            dense
            class="country-code phone-field"
            ><template v-slot:selection="{ item }">{{ item.code }}</template>
            <template v-slot:item="{ item }"
              >{{ item.code }} ({{ item.name }})</template
            ></v-select
          >
        </v-col>
      </v-row>

      <v-col cols="12" class="pa-0">
        <v-textarea
          v-model="form.bodysms"
          :label="$t('message')"
          rows="3"
          :counter="160"
          :rules="[(v) => v.length <= 160 || $t('max_160_chars')]"
          outlined
        ></v-textarea>
      </v-col>
    </v-form>
  </v-container>
</template>

<script>
import { countries } from "./countryCodes.js";

export default {
  name: "SmsTab",
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
        countrycodesms: "+98",
        sms: "",
        bodysms: "",
      },
      countries,
    };
  },
  computed: {
    isActive() {
      return this.getsection === "#sms" ? "show active" : "";
    },
    isRTL() {
      return this.$i18n.locale === "fa" || this.$i18n.locale === "ar";
    },
  },
  methods: {
    validate() {
      return this.$refs.smsForm.validate();
    },
  },
};
</script>

<style scoped>
/* .phone-field {
  border-radius: 0 4px 4px 0 !important;
} */
:deep(.v-text-field__slot) {
  padding-right: 8px !important;
}
:deep(.v-input__prepend-outer) {
  margin: 0 !important;
}
.country-code {
  width: 220px !important;
  border-radius: 4px 0 0 4px !important;
}
:deep(.v-select__slot) {
  padding: 0 8px !important;
}
:deep(.v-input__append-inner) {
  margin-top: 0 !important;
}

/* Ensure proper icon alignment in RTL */
[dir="rtl"] :deep(.v-input__append-inner) {
  margin-top: 0 !important;
  align-self: center !important;
}

/* Fix icon positioning for phone field */
.phone-field :deep(.v-input__append-inner) {
  margin-top: 0 !important;
  align-self: center !important;
  display: flex !important;
  align-items: center !important;
}
:deep(.v-input__append-inner) {
  margin-top: 0 !important;
  align-self: center !important;
}

:deep(.v-text-field .v-input__append-inner) {
  margin-top: 0 !important;
  align-self: center !important;
}
</style>
