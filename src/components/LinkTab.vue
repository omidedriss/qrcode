<template>
  <v-container class="link-tab-container fadeIn" v-if="isActive" id="link">
    <p class="rtl-label tag link-tag">{{ $t("link") }}</p>

    <v-form ref="linkTab" v-model="valid" lazy-validation class="link-form">
      <v-row class="link-row">
        <v-col cols="12" class="link-col">
          <v-text-field
            class="link-field"
            v-model="form.link"
            :label="$t('link')"
            :rules="[
              (v) => !!v || $t('field_required'),
              (v) => /^(http|https):\/\/[^ ]+$/.test(v) || $t('invalid_url'),
            ]"
            placeholder="https://"
            required
            outlined
            dense
            append-icon="fa fa-link"
          ></v-text-field>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>

<script>
export default {
  name: "LinkTab",
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
        link: "",
      },
    };
  },
  computed: {
    isActive() {
      return this.getsection === "#link";
    },
  },
  methods: {
    validate() {
      return this.$refs.linkForm.validate();
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

/* کاهش فاصله‌ها در حالت موبایل */
.link-tab-container {
  padding: 8px !important;
}

.link-tag {
  margin-bottom: 10px !important;
  margin-top: 5px !important;
}

.link-form {
  margin-bottom: 10px !important;
}

.link-row {
  margin: 0 !important;
}

.link-col {
  padding: 4px !important;
}

.link-field {
  margin-bottom: 8px !important;
}

/* تنظیمات برای تبلت */
@media (min-width: 481px) and (max-width: 768px) {
  .link-tab-container {
    padding: 10px !important;
  }

  .link-tag {
    margin-bottom: 15px !important;
    margin-top: 8px !important;
  }

  .link-field {
    margin-bottom: 10px !important;
  }
}

/* تنظیمات برای موبایل کوچک */
@media (max-width: 480px) {
  .link-tab-container {
    padding: 6px !important;
  }

  .link-tag {
    margin-bottom: 8px !important;
    margin-top: 3px !important;
  }

  .link-field {
    margin-bottom: 6px !important;
  }
}
</style>
