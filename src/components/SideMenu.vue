<template>
  <div class="menu-container">
    <div v-if="$vuetify.breakpoint.mdAndUp" class="goback">
      <a href="#" @click.prevent="$emit('go-back')">
        <i class="fas fa-arrow-left"></i> {{ $t("go_back") }}
      </a>
    </div>

    <div v-if="$vuetify.breakpoint.smAndDown" class="goback-mobile">
      <a href="#" @click.prevent="$emit('go-back')">
        <i class="fas fa-arrow-left"></i> {{ $t("go_back") }}
      </a>
    </div>

    <v-list v-if="$vuetify.breakpoint.mdAndUp" class="menu">
      <v-list-item
        v-for="(item, index) in menuItems"
        :key="index"
        :class="{ 'menu-item': true, active: activeMenu === index }"
        @click="setActiveMenu(index)"
      >
        <div class="content">
          <span class="menu-text">{{ item.text }}</span>
          <div class="icon-wrapper">
            <i :class="item.icon"></i>
          </div>
        </div>
      </v-list-item>
    </v-list>

    <div v-if="$vuetify.breakpoint.smAndDown" class="navigationTop">
      <div class="navigationBottom">
        <ul>
          <li
            v-for="(item, index) in menuItems"
            :key="index"
            :class="{ list: true, active: activeMenu === index }"
            @click="setActiveMenu(index)"
          >
            <a href="#" @click.prevent>
              <span class="icon"><i :class="item.icon"></i></span>
              <span class="text">{{ item.text }}</span>
            </a>
          </li>
        </ul>
        <div class="indicator" :style="{ left: indicatorPosition }">
          <i :class="menuItems[activeMenu].icon"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { EventBus } from "./eventBus.js";
export default {
  name: "SideMenu",
  props: {
    modelValue: {
      type: [Number, String],
      default: 0,
    },
    settings: {
      type: Object,
      default: () => ({
        menuBgColor: "#403199",
        activeMenuColor: "#ab9fee",
        iconColor: "#ffffff",
      }),
    },
  },
  data() {
    return {
      activeMenu: Number(this.modelValue) || 0,
    };
  },
  computed: {
    menuItems() {
      return [
        { text: this.$t("menuItems.info"), icon: "fas fa-info-circle" },
        { text: this.$t("menuItems.design"), icon: "fas fa-pencil-ruler" },
        { text: this.$t("menuItems.colors"), icon: "fas fa-palette" },
        { text: this.$t("menuItems.logo"), icon: "fas fa-image" },
        { text: this.$t("menuItems.frame"), icon: "fas fa-border-all" },
        { text: this.$t("menuItems.options"), icon: "fas fa-cogs" },
      ];
    },
    indicatorPosition() {
      const itemWidth = 100 / this.menuItems.length;
      const centerPos = this.activeMenu * itemWidth + itemWidth / 2;
      return `${centerPos}%`;
    },
  },
  mounted() {
    console.log("Initial activeMenu:", this.activeMenu);
  },
  watch: {
    activeMenu(newVal) {
      console.log("Active menu changed to:", newVal);
      this.$emit("update:modelValue", newVal);
      EventBus.$emit("update-active-menu", newVal);
    },
    modelValue(newVal) {
      console.log("Model value updated to:", newVal);
      this.activeMenu = Number(newVal) || 0;
    },
  },
  methods: {
    setActiveMenu(index) {
      console.log("Setting activeMenu to:", index);
      this.activeMenu = index;
    },
  },
};
</script>

<style scoped>
.menu-container {
  width: 100%;
  position: relative;
  height: 410px;
}
.goback {
  background: v-bind("settings.menuBgColor");
  padding: 15px 10px 15px 15px;
  border-radius: 0 15px 15px 0; /* شعاع گرد در سمت راست برای LTR */
  width: 100px;
  box-shadow: 3px 0 15px rgba(0, 0, 0, 0.4); /* سایه به سمت چپ برای LTR */
  z-index: 10;
  color: white;
  margin-top: 20px;
  margin-bottom: 20px;
}
.goback a {
  text-decoration: none;
  color: v-bind("settings.iconColor");
  width: 100%;
  padding-right: 12px;
}
.goback a:active {
  width: 30px;
  height: 30px;
  background: v-bind("settings.activeMenuColor");
  box-shadow: 3px 0 15px rgba(0, 0, 0, 0.6); /* سایه به سمت چپ برای LTR */
  color: v-bind("settings.iconColor");
}
.goback-mobile {
  background: v-bind("settings.menuBgColor");
  padding: 10px;
  border-radius: 10px;
  width: fit-content;
  margin: 10px auto;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  z-index: 10;
  color: white;
  float: left;
}
.goback-mobile a {
  text-decoration: none;
  color: v-bind("settings.iconColor");
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
  font-size: 0.9em;
}
.goback-mobile a:active {
  background: v-bind("settings.activeMenuColor");
  border-radius: 5px;
  padding: 5px 10px;
}
.menu {
  background: v-bind("settings.menuBgColor");
  padding: 10px 0;
  border-radius: 0 15px 15px 0; /* شعاع گرد در سمت راست برای LTR */
  width: 100px;
  box-shadow: 3px 0 15px rgba(0, 0, 0, 0.4); /* سایه به سمت چپ برای LTR */
  z-index: 10;
  color: white;
  height: 83%;
  margin-top: 10px;
  height: 450px;
  position: relative;
  left: 0;
}

/* RTL support - keep menu on left side with border-radius on left */
[dir="rtl"] .menu {
  border-radius: 15px 0 0 15px; /* شعاع گرد در سمت چپ برای RTL */
  box-shadow: -3px 0 15px rgba(0, 0, 0, 0.4); /* سایه به سمت راست برای RTL */
  left: 0;
  right: auto;
}

[dir="rtl"] .goback {
  border-radius: 15px 0 0 15px; /* شعاع گرد در سمت چپ برای RTL */
  box-shadow: -3px 0 15px rgba(0, 0, 0, 0.4); /* سایه به سمت راست برای RTL */
  left: 0;
  right: auto;
}
.menu-item {
  position: relative;
  padding: 10px 10px;
  color: v-bind("settings.iconColor");
  cursor: pointer;
}
.menu-item .content {
  display: flex;
  align-items: center;
  padding: 20px;
  border-radius: 5px; /* شعاع گرد در سمت راست برای LTR */
  background-color: v-bind("settings.menuBgColor");
  color: v-bind("settings.iconColor");
  position: relative;
}
.menu-text {
  opacity: 0;
  position: absolute;
  left: 0px; /* شروع از سمت چپ برای LTR */
  padding-left: 5px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 0 5px 5px 0; /* شعاع گرد در سمت راست برای LTR */
  font-weight: normal;
  transition: opacity 0.3s;
  white-space: nowrap;
  z-index: 2;
}

/* RTL support for menu text */
[dir="rtl"] .menu-text {
  left: 0px !important; /* شروع از سمت چپ برای RTL */
  right: 0px !important;
  border-radius: 5px 0 0 5px; /* شعاع گرد در سمت چپ برای RTL */
  padding-left: 10px !important;
  padding-right: 0px !important; /* padding-right برای RTL */
}

.menu-item.active .menu-text,
.menu-item:hover .menu-text {
  opacity: 1;
  width: 125%;
  padding-left: 10px;
  background-color: #ab9fee71;
  padding-top: 6px;
  padding-bottom: 6px;
  padding-right: 30px;
  box-shadow: inset 0 5px 7px -12px #ab9fee71, inset 0 -8px 10px -8px #ab9fee71; /* سایه برای LTR */
}

/* RTL support for active/hover menu text */
[dir="rtl"] .menu-item.active .menu-text,
[dir="rtl"] .menu-item:hover .menu-text {
  padding-left: 10px;
  padding-right: 0px;
  width: 125%;
  box-shadow: inset -5px 0 7px -12px #ab9fee71, inset 0 -8px 10px -8px #ab9fee71; /* سایه به سمت راست برای RTL */
}

.menu-item.active .content,
.menu-item:hover .content {
  box-shadow: inset 0 8px 10px -8px #ab9fee71, inset 0 -8px 10px -8px #ab9fee71; /* سایه برای LTR */
  border-radius: 10px;
  width: 115%;
}

/* RTL support for active/hover content */
[dir="rtl"] .menu-item.active .content,
[dir="rtl"] .menu-item:hover .content {
  box-shadow: inset -8px 0 10px -8px #ab9fee71, inset 0 -8px 10px -8px #ab9fee71; /* سایه به سمت راست برای RTL */
  border-radius: 10px 0 0 10px; /* شعاع گرد در سمت چپ برای RTL */
}

.icon-wrapper {
  position: absolute;
  left: 16px; /* آیکون در سمت راست منو برای LTR */
  width: 40px;
  height: 40px;
  background: v-bind("settings.menuBgColor");
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: all 0.3s ease;
  z-index: 3;
}

/* نوار کم‌رنگ برای اتصال به منو در حالت عادی */
.icon-wrapper::before {
  content: "";
  position: absolute;
  left: 40px; /* اتصال به سمت چپ منو در LTR */
  width: 20px;
  height: 40px;
  background: rgba(v-bind("settings.menuBgColor"), 0.5); /* کم‌رنگ‌تر */
  z-index: 2;
}

/* RTL support for icon wrapper */
[dir="rtl"] .icon-wrapper {
  left: auto;
  right: 16px; /* آیکون در سمت چپ منو برای RTL */
}

/* RTL support for connector bar */
[dir="rtl"] .icon-wrapper::before {
  left: auto;
  right: 40px; /* اتصال به سمت راست منو در RTL */
}

.menu-item.active .icon-wrapper,
.menu-item:hover .icon-wrapper {
  transform: translateX(55px); /* جابجایی به سمت چپ برای LTR */
  box-shadow: 12px 0 12px -4px rgba(0, 0, 0, 0.3); /* سایه به سمت چپ برای LTR */
  background: v-bind("settings.menuBgColor"); /* رنگ فعال در حالت فعال/هاور */
}

/* RTL support for active/hover icon wrapper */
[dir="rtl"] .menu-item.active .icon-wrapper,
[dir="rtl"] .menu-item:hover .icon-wrapper {
  transform: translateX(-50px); /* جابجایی به سمت چپ برای RTL */
  box-shadow: -12px 0 12px -4px rgba(0, 0, 0, 0.3); /* سایه به سمت راست برای RTL */
}
.icon-wrapper i {
  font-size: 20px;
  color: v-bind("settings.iconColor");
  transition: color 0.3s ease;
}
.menu-item.active .icon-wrapper i,
.menu-item:hover .icon-wrapper i {
  color: v-bind("settings.iconColor");
}
.navigationTop {
  position: relative;
  width: 95%;
  height: 103px;
  display: flex;
  justify-content: center;
  align-items: flex-end;
  background-color: white;
  border-radius: 16px;
  margin-bottom: 10px;
  justify-self: center;
}
.navigationBottom {
  width: 100%;
  height: 70px;
  background: v-bind("settings.menuBgColor");
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 16px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
  position: relative;
  box-shadow: 0 -10px 20px rgba(255, 255, 255, 0.8);
}
.navigationBottom ul {
  display: flex;
  justify-content: space-between;
  width: 100%;
  padding: 0 10px;
  list-style: none;
  position: relative;
  z-index: 1;
}

/* RTL support for mobile menu - reverse order */
[dir="rtl"] .navigationBottom ul {
  flex-direction: row-reverse; /* معکوس کردن ترتیب آیکون‌ها در RTL */
}

.navigationBottom ul li {
  flex: 1;
  height: 70px;
  text-align: center;
  cursor: pointer;
  position: relative;
}
.navigationBottom ul li a {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-decoration: none;
  height: 100%;
  color: white;
}
.navigationBottom ul li .icon {
  width: 36px;
  height: 36px;
  background: transparent;
  color: v-bind("settings.iconColor");
  border-radius: 50%;
  font-size: 1.3em;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: none;
}
.navigationBottom ul li.active .icon {
  color: transparent;
  background-color: transparent;
}
.navigationBottom ul li .text {
  font-size: 0.9em;
  opacity: 0;
  transition: 0.3s;
  position: absolute;
  top: 45%;
  color: v-bind("settings.iconColor");
}
.navigationBottom ul li.active .text {
  opacity: 1;
}
.indicator {
  position: absolute;
  color: v-bind("settings.iconColor");
  top: 11px;
  width: 48px;
  height: 48px;
  background: v-bind("settings.menuBgColor");
  border: 7px solid #f0f0ff;
  border-radius: 50%;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  display: flex;
  justify-content: center;
  align-items: center;
  transform: translateX(-50%) translateY(-44px);
  transition: left 0.4s ease, transform 0.4s ease;
  z-index: 0;
}
.indicator i {
  font-size: 1.5em;
  color: v-bind("settings.iconColor");
}
.indicator::before {
  content: "";
  position: absolute;
  top: calc(100% - 7px);
  left: -16px;
  width: 16px;
  height: 16px;
  background-color: transparent;
  border-top-right-radius: 16px;
  box-shadow: 1px -8px 0 0 white;
  z-index: -1;
}
.indicator::after {
  content: "";
  position: absolute;
  top: calc(100% - 7px);
  right: -16px;
  width: 16px;
  height: 16px;
  background-color: transparent;
  border-top-left-radius: 16px;
  box-shadow: -1px -8px 0 0 white;
  z-index: -1;
}
@media (max-width: 768px) {
  :deep(.v-window__container) {
    width: 99% !important;
    max-width: 99% !important;
  }
  .menu {
    display: none;
  }
  .goback {
    display: none;
  }
  .navigationTop {
    display: flex;
    width: 100% !important;
  }
  .goback-mobile {
    display: block;
  }
  .navigationBottom {
    width: 100%;
    margin-right: 10px;
    position: relative;
    position: relative;
  }

  .navigationBottom::after {
    content: "";
    position: absolute;
    bottom: -10px; /* کمی پایین‌تر از خود منو */
    left: 0;
    width: 100% !important;
    height: 20px;
    background: linear-gradient(
      to bottom,
      rgba(255, 255, 255, 1),
      rgba(255, 255, 255, 0.425)
    );
    z-index: -1;
  }
}
@media (min-width: 769px) {
  .menu {
    display: block;
    width: 80px;
  }
  .navigationTop,
  .navigationBottom {
    display: none;
  }
  .goback-mobile {
    display: none;
  }
}
</style>
