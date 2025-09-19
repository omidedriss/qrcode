<template>
  <v-container
    class="vcard-tab fadeIn"
    v-if="getsection === '#vcard'"
    id="vcard"
  >
    <!-- <v-card class="pa-4"> -->
    <p class="rtl-label tag">{{ $t("buttons.vcard") }}</p>

    <v-form ref="vcardTab" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-select
            v-model="form.vversion"
            :items="versions"
            :label="$t('version')"
            outlined
            dense
          >
          </v-select>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.vnametitle"
            :label="$t('name_title')"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.vname"
            :label="$t('first_name')"
            :rules="[(v) => !!v || $t('field_required')]"
            required
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.vlast"
            :label="$t('last_name')"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" class="pa-0">
          <!-- LTR Layout for Home Phone -->
          <v-row no-gutters v-if="!isRTL">
            <v-col cols="5" class="pa-0 pr-3">
              <v-select
                v-model="form.countrycodevphone"
                :items="countries"
                :label="$t('country_code')"
                :rules="[(v) => !form.vphone || !!v || $t('field_required')]"
                item-text="name"
                item-value="code"
                outlined
                dense
              >
                <template v-slot:selection="{ item }">{{ item.code }}</template>
                <template v-slot:item="{ item }"
                  >{{ item.code }} ({{ item.name }})</template
                ></v-select
              >
            </v-col>
            <v-col cols="7" class="pa-0 pr-3">
              <v-text-field
                v-model="form.vphone"
                :label="$t('phone_home')"
                :rules="[(v) => !v || /^\d+$/.test(v) || $t('invalid_phone')]"
                type="number"
                outlined
                dense
              ></v-text-field>
            </v-col>
          </v-row>

          <!-- RTL Layout for Home Phone -->
          <v-row no-gutters v-else>
            <v-col cols="7" class="pa-0 pl-3">
              <v-text-field
                v-model="form.vphone"
                :label="$t('phone_home')"
                :rules="[(v) => !v || /^\d+$/.test(v) || $t('invalid_phone')]"
                type="number"
                outlined
                dense
              ></v-text-field>
            </v-col>
            <v-col cols="5" class="pa-0 pl-3">
              <v-select
                v-model="form.countrycodevphone"
                :items="countries"
                :label="$t('country_code')"
                :rules="[(v) => !form.vphone || !!v || $t('field_required')]"
                item-text="name"
                item-value="code"
                outlined
                dense
              >
                <template v-slot:selection="{ item }">{{ item.code }}</template>
                <template v-slot:item="{ item }"
                  >{{ item.code }} ({{ item.name }})</template
                ></v-select
              >
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" md="6" class="pa-0">
          <!-- LTR Layout for Mobile Phone -->
          <v-row no-gutters v-if="!isRTL">
            <v-col cols="5" class="pa-0 pr-3">
              <v-select
                v-model="form.countrycodevmobile"
                :items="countries"
                :label="$t('country_code')"
                :rules="[(v) => !form.vmobile || !!v || $t('field_required')]"
                item-text="name"
                item-value="code"
                outlined
                dense
                ><template v-slot:selection="{ item }">{{
                  item.code
                }}</template>
                <template v-slot:item="{ item }"
                  >{{ item.code }} ({{ item.name }})</template
                ></v-select
              >
            </v-col>
            <v-col cols="7" class="pa-0 pr-3">
              <v-text-field
                v-model="form.vmobile"
                :label="$t('phone_mobile')"
                :rules="[(v) => !v || /^\d+$/.test(v) || $t('invalid_phone')]"
                type="number"
                outlined
                dense
              ></v-text-field>
            </v-col>
          </v-row>

          <!-- RTL Layout for Mobile Phone -->
          <v-row no-gutters v-else>
            <v-col cols="7" class="pa-0 pl-3">
              <v-text-field
                v-model="form.vmobile"
                :label="$t('phone_mobile')"
                :rules="[(v) => !v || /^\d+$/.test(v) || $t('invalid_phone')]"
                type="number"
                outlined
                dense
              ></v-text-field>
            </v-col>
            <v-col cols="5" class="pa-0 pl-3">
              <v-select
                v-model="form.countrycodevmobile"
                :items="countries"
                :label="$t('country_code')"
                :rules="[(v) => !form.vmobile || !!v || $t('field_required')]"
                item-text="name"
                item-value="code"
                outlined
                dense
                ><template v-slot:selection="{ item }">{{
                  item.code
                }}</template>
                <template v-slot:item="{ item }"
                  >{{ item.code }} ({{ item.name }})</template
                ></v-select
              >
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.vemail"
            :label="$t('email')"
            type="email"
            :rules="[(v) => !v || /.+@.+\..+/.test(v) || $t('invalid_email')]"
            outlined
            dense
            class="link-field"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.vurl"
            :label="$t('website')"
            type="url"
            placeholder="http://"
            :rules="[
              (v) =>
                !v || /^(http|https):\/\/[^ ]+$/.test(v) || $t('invalid_url'),
            ]"
            outlined
            class="link-field"
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.vcompany"
            :label="$t('company')"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.vtitle"
            :label="$t('jobtitle')"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" class="pa-0">
          <!-- LTR Layout for Office Phone -->
          <v-row no-gutters v-if="!isRTL">
            <v-col cols="5" class="pa-0 pr-3">
              <v-select
                v-model="form.countrycodevoffice"
                :items="countries"
                :label="$t('country_code')"
                :rules="[
                  (v) => !form.vofficephone || !!v || $t('field_required'),
                ]"
                item-text="name"
                item-value="code"
                outlined
                class="link-field"
                dense
                ><template v-slot:selection="{ item }">{{
                  item.code
                }}</template>
                <template v-slot:item="{ item }"
                  >{{ item.code }} ({{ item.name }})</template
                ></v-select
              >
            </v-col>
            <v-col cols="7" class="pa-0 pr-3">
              <v-text-field
                v-model="form.vofficephone"
                :label="$t('phone_office')"
                :rules="[(v) => !v || /^\d+$/.test(v) || $t('invalid_phone')]"
                type="number"
                outlined
                dense
                class="link-field"
                append-icon="mdi-phone"
              ></v-text-field>
            </v-col>
          </v-row>

          <!-- RTL Layout for Office Phone -->
          <v-row no-gutters v-else>
            <v-col cols="7" class="pa-0 pl-3">
              <v-text-field
                v-model="form.vofficephone"
                :label="$t('phone_office')"
                :rules="[(v) => !v || /^\d+$/.test(v) || $t('invalid_phone')]"
                type="number"
                outlined
                dense
                class="link-field"
                append-icon="mdi-phone"
              ></v-text-field>
            </v-col>
            <v-col cols="5" class="pa-0 pl-3">
              <v-select
                v-model="form.countrycodevoffice"
                :items="countries"
                :label="$t('country_code')"
                :rules="[
                  (v) => !form.vofficephone || !!v || $t('field_required'),
                ]"
                item-text="name"
                item-value="code"
                outlined
                class="link-field"
                dense
                ><template v-slot:selection="{ item }">{{
                  item.code
                }}</template>
                <template v-slot:item="{ item }"
                  >{{ item.code }} ({{ item.name }})</template
                ></v-select
              >
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" md="6" class="pa-0">
          <!-- LTR Layout for Fax -->
          <v-row no-gutters v-if="!isRTL">
            <v-col cols="5" class="pa-0 pr-3">
              <v-select
                v-model="form.countrycodevfax"
                :items="countries"
                :label="$t('country_code')"
                :rules="[(v) => !form.vfax || !!v || $t('field_required')]"
                item-text="name"
                item-value="code"
                outlined
                dense
                class="link-field"
                ><template v-slot:selection="{ item }">{{
                  item.code
                }}</template>
                <template v-slot:item="{ item }"
                  >{{ item.code }} ({{ item.name }})</template
                ></v-select
              >
            </v-col>
            <v-col cols="7">
              <v-text-field
                v-model="form.vfax"
                :label="$t('fax')"
                :rules="[(v) => !v || /^\d+$/.test(v) || $t('invalid_phone')]"
                type="number"
                outlined
                append-icon="mdi-fax"
                dense
                class="link-field"
              ></v-text-field>
            </v-col>
          </v-row>

          <!-- RTL Layout for Fax -->
          <v-row no-gutters v-else>
            <v-col cols="7" class="pa-0 pl-3">
              <v-text-field
                v-model="form.vfax"
                :label="$t('fax')"
                :rules="[(v) => !v || /^\d+$/.test(v) || $t('invalid_phone')]"
                type="number"
                outlined
                append-icon="mdi-fax"
                dense
                class="link-field"
              ></v-text-field>
            </v-col>
            <v-col cols="5" class="pa-0 pl-3">
              <v-select
                v-model="form.countrycodevfax"
                :items="countries"
                :label="$t('country_code')"
                :rules="[(v) => !form.vfax || !!v || $t('field_required')]"
                item-text="name"
                item-value="code"
                outlined
                dense
                class="link-field"
                ><template v-slot:selection="{ item }">{{
                  item.code
                }}</template>
                <template v-slot:item="{ item }"
                  >{{ item.code }} ({{ item.name }})</template
                ></v-select
              >
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" class="pa-0 pr-3">
          <v-textarea
            v-model="form.vaddress"
            :label="$t('address')"
            :counter="255"
            :rules="[(v) => v.length <= 255 || $t('max_255_chars')]"
            outlined
            dense
            append-icon="mdi-location"
          ></v-textarea>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.vcap"
            :label="$t('post_code')"
            outlined
            dense
            append-icon="mdi-post-code"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.vcity"
            :label="$t('city')"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.vstate"
            :label="$t('state')"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" class="pa-0 pr-3">
          <v-text-field
            v-model="form.vcountry"
            :label="$t('country')"
            outlined
            dense
            ><template v-slot:selection="{ item }">{{ item.code }}</template>
            <template v-slot:item="{ item }"
              >{{ item.code }} ({{ item.name }})</template
            ></v-text-field
          >
        </v-col>
        <!--
          <v-col cols="12">
            <v-textarea
              v-model="form.vnote"
              :label="$t('note')"
              :counter="255"

              :rules="[v => v.length <= 255 || $t('max_255_chars')]"
              outlined
            ></v-textarea>
          </v-col>
          -->
      </v-row>
    </v-form>
    <!-- </v-card> -->
  </v-container>
</template>

<script>
import { countries } from "./countryCodes.js";

export default {
  name: "VCardTab",
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
        vversion: "2.1",
        vnametitle: "",
        vname: "",
        vlast: "",
        countrycodevphone: "",
        vphone: "",
        countrycodevmobile: "",
        vmobile: "",
        vemail: "",
        vurl: "",
        vcompany: "",
        vtitle: "",
        countrycodevoffice: "",
        vofficephone: "",
        countrycodevfax: "",
        vfax: "",
        vaddress: "",
        vcap: "",
        vcity: "",
        vstate: "",
        vcountry: "",
        // vnote: ''
      },
      versions: [
        { text: "2.1", value: "2.1" },
        { text: "3.0", value: "3.0" },
        // { text: '4.0', value: '4.0' }
      ],
      countries,
    };
  },
  computed: {
    isActive() {
      return this.getsection === "#vcard" ? "show active" : "";
    },
    isRTL() {
      return this.$i18n.locale === "fa" || this.$i18n.locale === "ar";
    },
  },
  methods: {
    validate() {
      return this.$refs.vcardForm.validate();
    },
  },
};
</script>

<style scoped></style>
