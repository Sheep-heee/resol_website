const searchInput_pc = document.querySelector(".search_input.ebar");
const clearBtn_pc = document.querySelector(".clear_btn.ebar");
// const searchInput_mo = document.querySelector(".search_input.m_nav");
// const clearBtn_mo = document.querySelector(".clear_btn.m_nav");

const pcSearchClear = () => {
  searchInput_pc.value = "";
  searchInput_pc.focus();
};

// const moSearchClear = () => {
//   searchInput_mo.value = "";
//   searchInput_mo.focus();
// };

const searchDOMCheck = () => {
  // if (!clearBtn_pc && !clearBtn_mo) return;
  if (!clearBtn_pc) return;

  const isPc = window.innerWidth > 1130;

  if (clearBtn_pc) {
    clearBtn_pc.removeEventListener("click", pcSearchClear);
  }
  // if (clearBtn_mo) {
  //   clearBtn_mo.removeEventListener("click", moSearchClear);
  // }

  if (isPc && clearBtn_pc) {
    clearBtn_pc.addEventListener("click", pcSearchClear);
  }
  // else if (!isPc && clearBtn_mo) {
  //   clearBtn_mo.addEventListener("click", moSearchClear);
  // }
};

searchDOMCheck();

let resizeTimer;

window.addEventListener("resize", () => {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(() => {
    searchDOMCheck();
  }, 200);
});
