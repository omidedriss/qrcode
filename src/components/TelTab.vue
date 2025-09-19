<template>
  <v-container class="tel-tab fadeIn" v-if="isActive" id="tel">
    <p class="rtl-label tag">{{ $t("tel") }}</p>
    <v-form ref="telTab" v-model="valid" lazy-validation>
      <!-- LTR Layout (English, Turkish, Russian) -->
      <v-row v-if="!isRTL">
        <v-col cols="12" md="5" class="pa-0 px-3">
          <v-select
            v-model="form.countrycodetel"
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
        <v-col cols="12" md="7" class="pa-0">
          <v-text-field
            class="phone-field"
            v-model="form.tel"
            :label="$t('phone_number')"
            type="number"
            required
            outlined
            :rules="[
              (v) => !!v || $t('field_required'),
              (v) => /^\d+$/.test(v) || $t('invalid_phone'),
              (v) => v.length === 10 || $t('invalid_phone'),
            ]"
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
        <v-col cols="12" md="7" class="pa-0 px-3">
          <v-text-field
            class="phone-field"
            v-model="form.tel"
            :label="$t('phone_number')"
            type="number"
            required
            outlined
            :rules="[
              (v) => !!v || $t('field_required'),
              (v) => /^\d+$/.test(v) || $t('invalid_phone'),
              (v) => v.length === 10 || $t('invalid_phone'),
            ]"
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
        <v-col cols="12" md="5" class="pa-0 pl-3">
          <v-select
            v-model="form.countrycodetel"
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
    </v-form>
  </v-container>
</template>

<script>
import { countries } from "./countryCodes.js";

export default {
  name: "TelTab",
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
        countrycodetel: "+98",
        tel: "",
      },
      labelPhone: this.$t("phone_number"),
      countries,
    };
  },
  computed: {
    isActive() {
      return this.getsection === "#tel";
    },
    isRTL() {
      return this.$i18n.locale === "fa" || this.$i18n.locale === "ar";
    },
  },
  methods: {
    validate() {
      return this.$refs.telForm.validate();
    },
  },
};
</script>

<style scoped>
:deep(.v-text-field__slot) {
  padding-right: 8px !important;
}
:deep(.v-input__prepend-outer) {
  margin: 0 !important;
}

/* LTR Layout Styling */
.country-code {
  width: 220px !important;
}

/* LTR: Country code on left, phone on right */
[dir="ltr"] .country-code {
  border-radius: 4px 0 0 4px !important;
}

[dir="ltr"] .phone-field:not(.country-code) {
  border-radius: 0 4px 4px 0 !important;
}

/* RTL Layout Styling */
/* RTL: Phone on left, country code on right */
[dir="rtl"] .phone-field:not(.country-code) {
  border-radius: 4px 0 0 4px !important;
}

[dir="rtl"] .country-code {
  border-radius: 0 4px 4px 0 !important;
}

:deep(.v-select__slot) {
  padding: 0 8px !important;
}

/* Fix append icon positioning */
:deep(.v-input__append-inner) {
  margin-top: 0 !important;
  align-self: center !important;
}

:deep(.v-text-field .v-input__append-inner) {
  margin-top: 0 !important;
  align-self: center !important;
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
