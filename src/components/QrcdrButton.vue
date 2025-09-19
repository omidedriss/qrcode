<template>
  <div :style="containerStyle">
    <div
      class="snake-border-button"
      :class="{ 'dynamic-button': isDynamic }"
      :style="buttonStyle"
      @click="$emit('click')"
      @mouseover="isHovered = true"
      @mouseleave="isHovered = false"
    >
      <!-- Icon positioning for show_all_button -->
      <span
        v-if="isShowAll && isXs"
        class="icon show-all-icon"
        :style="showAllIconStyle"
      >
        <i :class="icon" :style="iconGradientStyle"></i>
      </span>

      <!-- Regular icon positioning -->
      <span v-else class="icon" :style="iconStyle">
        <i :class="icon" :style="iconGradientStyle"></i>
      </span>

      <!-- Text content for showAll button -->
      <span
        v-if="isShowAll && isXs"
        class="text show-all-text"
        :style="showAllTextStyle"
      >
        <span
          class="line1"
          :class="{ hovered: isHovered }"
          :style="{
            fontSize: getResponsiveFontSize('line1'),
            color: '#13156D',
            fontFamily: getResponsiveFontFamily('line1'),
            fontWeight: getResponsiveFontWeight('line1'),
            textAlign: 'center',
          }"
          >{{ $t(text) }}</span
        >
      </span>

      <!-- Regular text content -->
      <span
        v-else-if="isDynamic"
        class="text"
        :class="{ 'dynamic-text': isDynamic }"
        :style="{
          marginBottom: textMarginBottom,
          textAlign: isMobile
            ? 'center'
            : currentLanguage === 'fa'
            ? 'justify'
            : 'right',
          paddingRight: isMobile ? '0px' : '5px',
        }"
      >
        <span
          class="line1"
          :class="{ hovered: isHovered }"
          :style="{
            fontSize: getResponsiveFontSize('line1'),
            color: '#13156D',
            fontFamily: getResponsiveFontFamily('line1'),
            fontWeight: getResponsiveFontWeight('line1'),
            textAlign: isMobile
              ? 'center'
              : currentLanguage === 'fa'
              ? 'justify'
              : 'right',
          }"
          >{{ $t(text) }}</span
        >
        <span
          v-if="currentLanguage === 'fa'"
          class="line2"
          :style="{
            fontSize: getResponsiveFontSize('line2'),
            color: '#000000',
            fontFamily: getResponsiveFontFamily('line2'),
            fontWeight: getResponsiveFontWeight('line2'),
            textAlign: isMobile
              ? 'center'
              : currentLanguage === 'fa'
              ? 'justify'
              : 'right',
            marginTop: '1px',
          }"
          >{{ $t(subtext) || getFullText($t(text)) }}</span
        >
      </span>
      <span class="bottom-line" :style="bottomLineStyle"></span>
    </div>
    <span v-if="!isDynamic && !isShowAll" class="text" :style="staticTextStyle">
      <span
        class="line2-static"
        :class="{ hovered: isHovered }"
        :style="{
          fontSize: getResponsiveFontSize('line1'),
          color: '#13156D',
          fontFamily: getResponsiveFontFamily('line1'),
          fontWeight: getResponsiveFontWeight('line1'),
        }"
        >{{ $t(text) }}</span
      >
    </span>
  </div>
</template>

<script>
export default {
  name: "QrcdrButton",
  props: {
    icon: String,
    text: String,
    subtext: String,
    settings: Object,
    isDynamic: {
      type: Boolean,
      default: false,
    },
    isXs: {
      type: Boolean,
      default: false,
    },
    isMobile: {
      type: Boolean,
      default: false,
    },
    isShowAll: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      isHovered: false,
    };
  },
  computed: {
    currentLanguage() {
      return this.$i18n.locale;
    },
    containerStyle() {
      return {
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
      };
    },
    buttonStyle() {
      const colors = this.settings.gradientColors || [
        "#FF1616",
        "#FF1616",
        "#13156D",
        "#FFFFFF",
        "#FFFFFF",
        "#FFFFFF",
        "#FFFFFF",
        "#FFFFFF",
        "#FFFFFF",
        "#13156D",
        "#FF1616",
        "#FF1616",
      ];
      const baseShadow = "0 4px 10px rgba(0, 0, 0, 0.2)";
      const hoverShadow = "0 8px 20px rgba(0, 0, 0, 0.4)";

      // Special styling for showAll button
      if (this.isShowAll) {
        return {
                  width: "100% !important", // 100% width for showAll button
        maxWidth: "100% !important", // Ensure maximum width
        height: this.isDynamic ? "70px" : "60px", // Maintain current height
          borderRadius: "5px",
          border: "2px solid transparent",
          background: `
        linear-gradient(#FBFAFA, #FBFAFA) padding-box,
        conic-gradient(from var(--gradient-angle), ${colors
          .slice(0, 5)
          .join(", ")}, ${colors.slice(5).join(", ")}) border-box
      `,
          position: "relative",
          display: "flex",
          flexDirection: "row",
          paddingLeft: "15px",
          paddingRight: "15px",
          paddingTop: "0px",
          paddingBottom: "0px",
          textAlign: "center",
          color: this.settings.textColor || "#13156D",
          cursor: "pointer",
          boxSizing: "border-box",
          boxShadow: this.isHovered ? hoverShadow : baseShadow,
          transition: "all 0.3s ease",
          "--gradient-angle": "0deg",
          alignItems: "center",
          justifyContent: "space-between", // Distribute content for icon and text
        };
      }

      // Original button styling (unchanged)
      return {
        width: this.isDynamic
          ? this.isMobile
            ? this.isXs
              ? "130px"
              : "153px"
            : "186px"
          : this.isMobile
          ? "48px"
          : "74px",
        height: this.isDynamic
          ? this.isMobile
            ? this.isXs
              ? "70px"
              : "71px"
            : "69px"
          : this.isMobile
          ? "47px"
          : "66px",
        borderRadius: "5px",
        border: "2px solid transparent",
        background: `
      linear-gradient(#FBFAFA, #FBFAFA) padding-box,
      conic-gradient(from var(--gradient-angle), ${colors
        .slice(0, 5)
        .join(", ")}, ${colors.slice(5).join(", ")}) border-box
    `,
        position: "relative",
        display: "flex",
        flexDirection: this.isDynamic ? "row" : "column",
        paddingLeft:
          this.isDynamic && this.isMobile
            ? "0px !important"
            : this.isDynamic && !this.isMobile
            ? "0px"
            : "0px",
        paddingRight:
          this.isDynamic && this.isMobile
            ? "0px"
            : this.isDynamic && !this.isMobile
            ? "27px !important"
            : "0px",
        paddingTop: "0px",
        paddingBottom: "0px",
        textAlign: "center",
        color: this.settings.textColor || "#13156D",
        cursor: "pointer",
        boxSizing: "border-box",
        boxShadow: this.isHovered ? hoverShadow : baseShadow,
        transition: "all 0.3s ease",
        "--gradient-angle": "0deg",
        alignItems:
          this.isDynamic && this.isMobile
            ? "center"
            : this.isDynamic && !this.isMobile
            ? "flex-start"
            : "center",
        justifyContent:
          this.isDynamic && this.isMobile
            ? "center"
            : this.isDynamic && !this.isMobile
            ? "flex-start"
            : "center",
      };
    },
    // buttonStyle() {
    //   const colors = this.settings.gradientColors || [
    //     "#FF1616",
    //     "#FF1616",
    //     "#13156D",
    //     "#FFFFFF",
    //     "#FFFFFF",
    //     "#FFFFFF",
    //     "#FFFFFF",
    //     "#FFFFFF",
    //     "#FFFFFF",
    //     "#13156D",
    //     "#FF1616",
    //     "#FF1616",
    //   ];
    //   const baseShadow = "0 4px 10px rgba(0, 0, 0, 0.2)";
    //   const hoverShadow = "0 8px 20px rgba(0, 0, 0, 0.4)";

    //   // Special styling for showAll button (mobile only)
    //   if (this.isShowAll && this.isMobileBreakpoint) {
    //     // Get button dimensions based on tab type
    //     const buttonHeight = this.isDynamic ? "120px" : "85px"; // Dynamic: 120px, Static: 85px
    //     const buttonWidth = this.isDynamic
    //       ? "calc(200% + 46px)"
    //       : "calc(200% + 95px)"; // 2 buttons + gap

    //     return {
    //       width: buttonWidth,
    //       height: buttonHeight,
    //       borderRadius: "5px",
    //       border: "2px solid transparent",
    //       background: `
    //         linear-gradient(#FBFAFA, #FBFAFA) padding-box,
    //         conic-gradient(from var(--gradient-angle), ${colors
    //           .slice(0, 5)
    //           .join(", ")}, ${colors.slice(5).join(", ")}) border-box
    //       `,
    //       position: "relative",
    //       display: "flex",
    //       flexDirection: "row",
    //       paddingLeft: "15px",
    //       paddingRight: "15px",
    //       paddingTop: "0px",
    //       paddingBottom: "0px",
    //       textAlign: "center",
    //       color: this.settings.textColor || "#13156D",
    //       cursor: "pointer",
    //       boxSizing: "border-box",
    //       boxShadow: this.isHovered ? hoverShadow : baseShadow,
    //       transition: "all 0.3s ease",
    //       "--gradient-angle": "0deg",
    //       alignItems: "center",
    //       justifyContent: "flex-start",
    //     };
    //   }

    //   // Original button styling (unchanged)
    //   return {
    //     width: this.isDynamic
    //       ? this.isMobile
    //         ? this.isXs
    //           ? "130px"
    //           : "153px"
    //         : "186px"
    //       : this.isMobile
    //       ? "48px"
    //       : "74px",
    //     height: this.isDynamic
    //       ? this.isMobile
    //         ? this.isXs
    //           ? "70px"
    //           : "71px"
    //         : "69px"
    //       : this.isMobile
    //       ? "47px"
    //       : "66px",
    //     borderRadius: "5px",
    //     border: "2px solid transparent",
    //     background: `
    //       linear-gradient(#FBFAFA, #FBFAFA) padding-box,
    //       conic-gradient(from var(--gradient-angle), ${colors
    //         .slice(0, 5)
    //         .join(", ")}, ${colors.slice(5).join(", ")}) border-box
    //     `,
    //     position: "relative",
    //     display: "flex",
    //     flexDirection: this.isDynamic ? "row" : "column",
    //     paddingLeft:
    //       this.isDynamic && this.isMobile
    //         ? "0px !important"
    //         : this.isDynamic && !this.isMobile
    //         ? "0px"
    //         : "0px",
    //     paddingRight:
    //       this.isDynamic && this.isMobile
    //         ? "0px"
    //         : this.isDynamic && !this.isMobile
    //         ? "27px !important"
    //         : "0px",
    //     paddingTop: "0px",
    //     paddingBottom: "0px",
    //     textAlign: "center",
    //     color: this.settings.textColor || "#13156D",
    //     cursor: "pointer",
    //     boxSizing: "border-box",
    //     boxShadow: this.isHovered ? hoverShadow : baseShadow,
    //     transition: "all 0.3s ease",
    //     "--gradient-angle": "0deg",
    //     alignItems:
    //       this.isDynamic && this.isMobile
    //         ? "center"
    //         : this.isDynamic && !this.isMobile
    //         ? "flex-start"
    //         : "center",
    //     justifyContent:
    //       this.isDynamic && this.isMobile
    //         ? "center"
    //         : this.isDynamic && !this.isMobile
    //         ? "flex-start"
    //         : "center",
    //   };
    // },
    iconStyle() {
      // Special styling for showAll button icon
      if (this.isShowAll && this.isMobileBreakpoint) {
        return {
          width: "35px",
          height: "35px",
          borderRadius: "4px",
          border: "1px solid rgba(0, 0, 0, 0.13)",
          background: "#FFFFFF",
          position: "relative",
          right: "0",
          top: "0",
          transform: "none",
          padding: "0",
          marginBottom: "0",
          marginLeft: "15px",
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
          boxShadow: "0 0 9.5px -1px rgba(0, 0, 0, 0.22)",
          zIndex: 2,
          transition: "all 0.3s ease",
        };
      }

      // Original icon styling (unchanged)
      return {
        width: this.isDynamic
          ? this.isMobile
            ? "32px"
            : "37px"
          : this.isMobile
          ? "25px"
          : "43px",
        height: this.isDynamic
          ? this.isMobile
            ? "28px"
            : "36px"
          : this.isMobile
          ? "25px"
          : "45px",
        borderRadius: "4px",
        border: "1px solid rgba(0, 0, 0, 0.13)",
        background: "#FFFFFF",
        position: "absolute",
        right:
          this.isDynamic && !this.isMobile
            ? "-8%"
            : this.isDynamic && this.isMobile
            ? "40%"
            : "unset",
        top:
          this.isDynamic && this.isMobile
            ? "-25%"
            : this.isDynamic
            ? "50%"
            : "unset",
        left: this.isDynamic ? "50%" : "unset",
        transform: this.isDynamic
          ? this.isMobile
            ? this.isHovered
              ? "rotate(360deg)"
              : "none"
            : this.isHovered
            ? "rotate(360deg) translateY(-50%)"
            : "translateY(-50%)"
          : this.isHovered
          ? "rotate(360deg)"
          : "none",
        padding:
          this.isDynamic && this.isMobile
            ? "0px"
            : this.isDynamic && !this.isMobile
            ? "12px"
            : "3px",
        marginBottom: this.isDynamic ? "0" : "2px",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        boxShadow: "0 0 9.5px -1px rgba(0, 0, 0, 0.22)",
        zIndex: 2,
        transition: "all 0.3s ease",
      };
    },
    iconGradientStyle() {
      const colors = this.settings.gradientColorsIcon || [
        "#FF1616",
        "#13156D",
        "#FFFFFF",
      ];
      return {
        fontSize: this.isDynamic
          ? this.isMobile
            ? "17px"
            : "25px"
          : this.isMobile
          ? "17px"
          : "25px",
        background: this.isHovered
          ? // ? `radial-gradient(circle at center, ${colors[0]} 6%, ${colors[1]} 100%)`
            // : `radial-gradient(circle at center, ${colors[1]} 6%, ${colors[0]} 100%)`,
            `linear-gradient(43deg,  ${colors[2]},${colors[1]}, ${colors[0]}, ${colors[2]})`
          : `linear-gradient(43deg,  ${colors[2]},${colors[0]}, ${colors[1]}, ${colors[2]})`,
        backgroundClip: "text",
        WebkitBackgroundClip: "text",
        color: "transparent",
        transition: "all 0.3s ease",
      };
    },
    bottomLineStyle() {
      return {
        position: "absolute",
        bottom: "-4% !important",
        justifySelf: "center",
        padding: "0px",
        margin: "0px",
        left: "0px !important",
        right: "0px !important",
        width: this.isDynamic
          ? this.isMobile
            ? "94px"
            : "151px"
          : this.isMobile
          ? "29px"
          : "56px",
        height: this.isDynamic
          ? this.isMobile
            ? "2px"
            : "3px"
          : this.isMobile
          ? "2px"
          : "2px",
        backgroundColor: "#13156D",
        borderRadius: "1px",
        transition: "opacity 0.3s ease",
        opacity: this.isHovered ? 0 : 1,
        zIndex: 10,
      };
    },
    textMarginBottom() {
      return this.isDynamic
        ? this.isMobile
          ? "2px"
          : "4px"
        : this.isMobile
        ? "8px"
        : "9px";
    },
    staticTextStyle() {
      return {
        marginTop: this.isMobile ? "8px" : "0px",
        marginLeft: this.isDynamic ? "0" : "0px",
        marginRight: this.isDynamic ? "0" : "0px",
        padding: this.isDynamic ? "0" : "5px",
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        justifyContent: "center",
        textAlign: "center",
      };
    },
    smallerTextSize() {
      return `calc(${this.settings.textSize} * 0.85)`;
    },
    showAllIconStyle() {
      // Special styling for showAll button icon
      if (this.isShowAll && this.isMobileBreakpoint) {
        return {
          width: "35px",
          height: "35px",
          borderRadius: "4px",
          border: "1px solid rgba(0, 0, 0, 0.13)",
          background: "#FFFFFF",
          position: "relative",
          right: "0",
          top: "0",
          transform: "none",
          padding: "0",
          marginBottom: "0",
          marginLeft: "15px",
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
          boxShadow: "0 0 9.5px -1px rgba(0, 0, 0, 0.22)",
          zIndex: 2,
          transition: "all 0.3s ease",
        };
      }

      // Return empty object if not showAll button
      return {};
    },
    showAllTextStyle() {
      // Special styling for showAll button text
      if (this.isShowAll && this.isXs) {
        return {
          flex: "1",
          textAlign: "center",
          paddingLeft: "0px",
          paddingRight: "0px",
          display: "flex",
          flexDirection: "column",
          alignItems: "center",
          justifyContent: "center",
          minHeight: "100%",
          marginRight: "15px",
        };
      }

      // Return empty object if not showAll button
      return {};
    },
  },
  methods: {
    getLimitedText(text, maxWords) {
      if (!text) return "";
      const words = text.split(" ");
      if (words.length <= maxWords) {
        return text;
      }
      return words.slice(0, maxWords).join(" ");
    },
    getFullText(text) {
      if (!text) return "";
      return text;
    },
    getResponsiveFontSize(lineType) {
      const isMobile = this.isMobile;
      const isXs = this.isXs;

      if (lineType === "line1") {
        if (isXs) {
          return this.settings[`textSize_line1_mobile_small`] || "12px";
        } else if (isMobile) {
          return this.settings[`textSize_line1_mobile`] || "14px";
        } else if (window.innerWidth < 768) {
          return this.settings[`textSize_line1_tablet`] || "16px";
        } else {
          return this.settings[`textSize_line1_desktop`] || "18px";
        }
      } else if (lineType === "line2") {
        if (isXs) {
          return this.settings[`textSize_line2_mobile_small`] || "10px";
        } else if (isMobile) {
          return this.settings[`textSize_line2_mobile`] || "12px";
        } else if (window.innerWidth < 768) {
          return this.settings[`textSize_line2_tablet`] || "14px";
        } else {
          return this.settings[`textSize_line2_desktop`] || "16px";
        }
      }

      return this.settings.textSize || "14px";
    },
    getResponsiveFontFamily(lineType) {
      const lang = this.currentLanguage;

      if (lang === "fa") {
        if (lineType === "line1") {
          return (
            this.settings[`fontFamily_${lineType}`] ||
            '"IRANYekanX", Sans-serif'
          );
        } else if (lineType === "line2") {
          return (
            this.settings[`fontFamily_${lineType}`] || '"Vazir", Sans-serif'
          );
        }
      } else {
        return (
          this.settings[`fontFamily_${lineType}_${lang}`] ||
          '"Arial", Sans-serif'
        );
      }
    },
    getResponsiveFontWeight(lineType) {
      const lang = this.currentLanguage;

      if (lang === "fa") {
        if (lineType === "line1") {
          return this.settings[`fontWeight_${lineType}`] || "900";
        } else if (lineType === "line2") {
          return this.settings[`fontWeight_${lineType}`] || "400";
        }
      } else {
        return this.settings[`fontWeight_${lineType}_${lang}`] || "700";
      }
    },
  },
};
</script>

<style scoped>
.snake-border-button {
  cursor: pointer;
}

.snake-border-button .icon {
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2;
}

.snake-border-button:hover .icon,
.snake-border-button:active .icon {
  animation: modernIconBounce 0.6s ease-in-out forwards;
}

.snake-border-button .text {
  z-index: 1;
  text-align: center;
  padding-left: 4px;
  padding-right: 4px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100%;
}

.snake-border-button .dynamic-text {
  align-items: flex-end;
  justify-content: center;
  padding-right: 5px;
  min-height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.snake-border-button .line1,
.snake-border-button .line2,
.snake-border-button .line2-static {
  display: block;
  width: 100%;
  text-align: center;
  white-space: normal;
  transition: all 0.3s ease;
}

.snake-border-button.dynamic-button .line1,
.snake-border-button.dynamic-button .line2 {
  text-align: justify;
  width: auto;
  min-width: 100px;
}

/* Mobile responsive for dynamic buttons */
@media (max-width: 768px) {
  .snake-border-button.dynamic-button .line1,
  .snake-border-button.dynamic-button .line2 {
    text-align: center;
    width: 100%;
    min-width: auto;
  }

  .snake-border-button .dynamic-text {
    align-items: center;
    padding-right: 0px;
  }
}

.snake-border-button .line1.hovered,
.snake-border-button .line2-static.hovered {
  color: #ff1616 !important;
  transform: scale(1.05);
  text-shadow: 0 0 8px rgba(255, 22, 22, 0.3);
}

.snake-border-button:hover .icon i {
  animation: gradientShift 2s ease-in-out infinite;
}

.snake-border-button.dynamic-button {
  flex-direction: row;
  justify-content: flex-start;
  padding-left: 20px;
  padding-right: 20px;
}

.snake-border-button:hover,
.snake-border-button:active {
  animation: spin 2s linear infinite;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

@keyframes modernIconBounce {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.15) rotate(10deg);
  }
  100% {
    transform: scale(1.05);
  }
}

@keyframes spin {
  to {
    --gradient-angle: 360deg;
  }
}

@keyframes gradientShift {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

@property --gradient-angle {
  inherits: false;
  initial-value: 0deg;
  syntax: "<angle>";
}

/* Mobile animation alternatives */
@media (max-width: 768px) {
  .snake-border-button .icon {
    transition: all 0.2s ease;
  }

  .snake-border-button:active .icon {
    animation: mobileIconTap 0.3s ease-in-out;
  }

  .snake-border-button:active {
    animation: mobileButtonTap 0.3s ease-in-out;
  }
}

@keyframes mobileIconTap {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.2);
  }
  100% {
    transform: scale(1);
  }
}

@keyframes mobileButtonTap {
  0% {
    transform: scale(0.98);
  }
  50% {
    transform: scale(0.98);
  }
  100% {
    transform: scale(1);
  }
}

* {
  font-size: small !important;
  direction: rtl;
}

/* Show All Button specific styles */
.snake-border-button.show-all-button {
  flex-direction: row !important;
  justify-content: space-between !important;
  align-items: center !important;
  width: 100% !important; /* عرض 100% برای دکمه showAll */
  max-width: 100% !important; /* حداکثر عرض */
}

.snake-border-button.show-all-button .show-all-icon {
  order: 2; /* آیکون بعد از متن */
}

.snake-border-button.show-all-button .show-all-text {
  order: 1; /* متن قبل از آیکون */
  flex: 1;
  text-align: center !important;
}
.snake-border-button.show-all-button .show-all-icon {
  order: 2; /* Icon comes after text */
}

.snake-border-button.show-all-button .show-all-text {
  order: 1; /* Text comes before icon */
  flex: 1;
  text-align: center !important;
}

.snake-border-button.show-all-button .line1 {
  text-align: center !important;
  width: 100% !important;
  min-width: auto !important;
}

/* Mobile responsive for show all button */
@media (max-width: 768px) {
  .snake-border-button.show-all-button {
    width: 100% !important;
    max-width: 100% !important;
  }
}

@media (max-width: 480px) {
  .snake-border-button.show-all-button .show-all-icon {
    width: 32px !important;
    height: 32px !important;
  }
}

@media (max-width: 360px) {
  .snake-border-button.show-all-button .show-all-icon {
    width: 30px !important;
    height: 30px !important;
  }
}
</style>
