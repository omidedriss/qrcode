<template>
  <div class="navigation">
    <ul>
      <li
        v-for="(item, index) in settings.menuItems"
        :key="item.text"
        class="list"
        :class="{ active: index === activeItem }"
        @click="activeItem = index"
      >
        <a href="#">
          <span class="icon"><i :class="item.icon"></i></span>
          <span class="text">{{ item.text }}</span>
        </a>
      </li>
      <div class="indicator"></div>
    </ul>
  </div>
</template>

<script>
export default {
  name: "MobileNavigation",
  props: ["settings"],
  data() {
    return {
      activeItem: 0,
    };
  },
  mounted() {
    this.updateIndicator();
  },
  methods: {
    updateIndicator() {
      const indicator = this.$el.querySelector(".indicator");
      const target = this.$el.querySelectorAll(".list")[this.activeItem];
      const itemWidth = target.offsetWidth;
      const itemLeft = target.offsetLeft;
      const centerPos = itemLeft + itemWidth / 2 - 20;
      indicator.style.left = `${centerPos}px`;
    },
  },
  watch: {
    activeItem() {
      this.$nextTick(() => this.updateIndicator());
    },
  },
};
</script>

<style scoped>
.navigation {
  position: fixed;
  bottom: 20px;
  width: 90%;
  max-width: 500px;
  height: 70px;
  background: v-bind("settings.menuBgColor");
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 14px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
  left: 50%;
  transform: translateX(-50%);
}
.navigation ul {
  display: flex;
  justify-content: space-between;
  width: 100%;
  padding: 0 10px;
  list-style: none;
}
.navigation ul li {
  flex: 1;
  height: 70px;
  text-align: center;
  cursor: pointer;
}
.navigation ul li .icon {
  width: 36px;
  height: 36px;
  background: v-bind("settings.menuBgColor");
  border-radius: 50%;
  font-size: 1.5em;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: all 0.4s;
}
.navigation ul li.active .icon {
  transform: translateY(-38px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}
.navigation ul li .text {
  font-size: 0.9em;
  opacity: 0;
  transition: 0.3s;
  position: absolute;
  top: 45%;
  color: white;
}
.navigation ul li.active .text {
  opacity: 1;
}
.indicator {
  position: absolute;
  top: -22px;
  width: 40px;
  height: 40px;
  background: v-bind("settings.menuBgColor");
  border-radius: 50%;
  border: 7px solid v-bind("settings.indicatorBorderColor");
  transition: left 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}
.indicator::before,
.indicator::after {
  content: "";
  position: absolute;
  top: 57%;
  width: 20px;
  height: 20px;
  background: transparent;
}
.indicator::before {
  left: -22px;
  clip-path: polygon(100% 0, 0 0, 100% 100%);
}
.indicator::after {
  right: -22px;
  clip-path: polygon(0 0, 100% 0, 0 100%);
}
@media (min-width: 769px) {
  .navigation {
    display: none;
  }
}
</style>
