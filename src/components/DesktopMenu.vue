<template>
  <div class="menu">
    <div
      v-for="(item, index) in settings.menuItems"
      :key="item.text"
      class="menu-item"
      :class="{ active: index === activeItem }"
      @click="activeItem = index"
    >
      <div class="content">
        <span>{{ item.text }}</span>
        <div class="icon-wrapper"><i :class="item.icon"></i></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "DesktopMenu",
  props: ["settings"],
  data() {
    return {
      activeItem: 0,
    };
  },
};
</script>

<style scoped>
.menu {
  position: fixed;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  background: v-bind("settings.menuBgColor");
  padding: 10px 0;
  border-radius: 0 15px 15px 0;
  width: 80px;
  box-shadow: 3px 0 15px rgba(0, 0, 0, 0.4);
  z-index: 10;
}
.menu-item {
  padding: 5px 10px;
  color: white;
  cursor: pointer;
}
.menu-item .content {
  position: relative;
  display: flex;
  align-items: center;
  padding: 10px;
}
.menu-item span {
  opacity: 0;
  position: absolute;
  left: 0;
  background: rgba(255, 255, 255, 0.1);
  padding: 10px 15px;
  border-radius: 6px;
  transition: opacity 0.3s, transform 0.3s;
}
.menu-item:hover span {
  opacity: 1;
  transform: translateX(50px);
}
.menu-item .icon-wrapper {
  width: 40px;
  height: 40px;
  background: v-bind("settings.menuBgColor");
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: transform 0.3s;
}
.menu-item:hover .icon-wrapper {
  transform: translateX(45px);
  box-shadow: 10px 0 10px -4px rgba(0, 0, 0, 0.3);
}
.menu-item.active::after {
  content: "";
  position: absolute;
  left: 10px;
  top: 7.5%;
  height: 85%;
  width: 3px;
  background-color: v-bind("settings.bottomLineColor");
  border-radius: 2px;
}
@media (max-width: 768px) {
  .menu {
    display: none;
  }
}
</style>
