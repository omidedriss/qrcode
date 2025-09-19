<template>
  <v-container
    class="paypal-tab fadeIn"
    v-if="getsection === '#paypal'"
    id="paypal"
  >
    <!-- <v-card class="pa-4"> -->
    <p class="rtl-label tag">{{ $t("buttons.paypal") }}</p>
    <v-form ref="paypalTab" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12" sm="6" class="pa-0 pr-3">
          <v-select
            v-model="form.pp_type"
            :items="paypalTypes"
            :label="$t('type')"
            outlined
            @change="updateFieldVisibility"
            dense
          ></v-select>
        </v-col>
        <v-col cols="12" sm="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.pp_email"
            :label="$t('email')"
            :rules="[
              (v) => !!v || $t('field_required'),
              (v) => /.+@.+\..+/.test(v) || $t('invalid_email'),
            ]"
            type="email"
            required
            outlined
            :placeholder="$t('pp_email')"
            dense
            class="link-field"
          >
            <template v-slot:append>
              <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                  <v-icon v-on="on">mdi-information</v-icon>
                </template>
                {{ $t("pp_email") }}
              </v-tooltip>
            </template>
          </v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" sm="8" class="pa-0 pr-3">
          <v-text-field
            v-model="form.pp_name"
            :label="$t('item_name')"
            :rules="[(v) => !!v || $t('field_required')]"
            required
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" sm="4" class="pa-0 pr-3">
          <v-text-field
            v-model="form.pp_id"
            :label="$t('item_id')"
            outlined
            dense
            class="ltr"
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col v-if="showDonationFields" cols="12" sm="6" class="pa-0 pr-3">
          <v-text-field
            v-model.number="form.pp_price"
            :label="$t('price')"
            type="number"
            step="0.01"
            min="0"
            outlined
            dense
            :append-icon="'mdi-currency-' + form.pp_currency.toLowerCase()"
          ></v-text-field>
        </v-col>
        <v-col cols="12" sm="6" class="pa-0 pr-3">
          <v-select
            v-model="form.pp_currency"
            :items="currencies"
            :label="$t('currency')"
            outlined
            @change="updateCurrencyDisplay"
            dense
          ></v-select>
        </v-col>
        <v-col v-if="showDonationFields" cols="12" sm="6" class="pa-0 pr-3">
          <v-text-field
            v-model.number="form.pp_shipping"
            :label="$t('shipping')"
            type="number"
            step="0.01"
            min="0"
            outlined
            dense
            :append-icon="'mdi-currency-' + form.pp_currency.toLowerCase()"
          ></v-text-field>
        </v-col>
        <v-col v-if="showDonationFields" cols="12" sm="6" class="pa-0 pr-3">
          <v-text-field
            v-model.number="form.pp_tax"
            :label="$t('tax_rate')"
            type="number"
            step="0.01"
            min="0"
            outlined
            dense
            append-icon="mdi-percent"
          ></v-text-field>
        </v-col>
      </v-row>
    </v-form>
    <!-- </v-card> -->
  </v-container>
</template>

<script>
export default {
  name: "PaypalTab",
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
        pp_type: "_xclick",
        pp_email: "",
        pp_name: "",
        pp_id: "",
        pp_price: null,
        pp_currency: "USD",
        pp_shipping: null,
        pp_tax: null,
      },
      paypalTypes: [
        { text: this.$t("buy_now"), value: "_xclick" },
        { text: this.$t("add_to_cart"), value: "_cart" },
        { text: this.$t("donations"), value: "_donations" },
      ],
      currencies: [
        { text: "United States dollar", value: "USD" },
        { text: "Euro", value: "EUR" },
        { text: "Australian dollar", value: "AUD" },
        { text: "Brazilian real", value: "BRL" },
        { text: "Canadian dollar", value: "CAD" },
        { text: "Chinese Renmenbi", value: "CNY" },
        { text: "Czech koruna", value: "CZK" },
        { text: "Danish krone", value: "DKK" },
        { text: "Hong Kong dollar", value: "HKD" },
        { text: "Hungarian forint", value: "HUF" },
        { text: "Indian rupee", value: "INR" },
        { text: "Israeli new shekel", value: "ILS" },
        { text: "Japanese yen", value: "JPY" },
        { text: "Malaysian ringgit", value: "MYR" },
        { text: "Mexican peso", value: "MXN" },
        { text: "New Taiwan dollar", value: "TWD" },
        { text: "New Zealand dollar", value: "NZD" },
        { text: "Norwegian krone", value: "NOK" },
        { text: "Philippine peso", value: "PHP" },
        { text: "Polish złoty", value: "PLN" },
        { text: "Pound sterling", value: "GBP" },
        { text: "Russian ruble", value: "RUB" },
        { text: "Singapore dollar", value: "SGD" },
        { text: "Swedish krona", value: "SEK" },
        { text: "Swiss franc", value: "CHF" },
        { text: "Thai baht", value: "THB" },
      ],
    };
  },
  computed: {
    isActive() {
      return this.getsection === "#paypal" ? "show active" : "";
    },
    showDonationFields() {
      return this.form.pp_type !== "_donations";
    },
  },
  methods: {
    updateFieldVisibility() {
      if (this.form.pp_type === "_donations") {
        this.form.pp_price = null;
        this.form.pp_shipping = null;
        this.form.pp_tax = null;
      }
    },
    updateCurrencyDisplay() {
      this.$forceUpdate();
    },
    validate() {
      return this.$refs.paypalForm.validate();
    },
  },
};
</script>

<style scoped>
.v-card {
  padding: 16px;
}
.ltr {
  direction: ltr;
}
</style>
