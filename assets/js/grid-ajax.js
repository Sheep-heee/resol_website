const buttons = document.querySelectorAll(".btn-load-more");

buttons.forEach((btn) => {
  let isLoading = false;

  btn.addEventListener("click", async () => {
    if (isLoading) return;
    isLoading = true;

    const rootKey = btn.dataset.rootKey;
    const offset = parseInt(btn.dataset.offset) || 0;
    const perPage = parseInt(btn.dataset.perPage) || 6;
    const termId = parseInt(btn.dataset.termId) || 0;

    const grid = document.getElementById(`js-${rootKey}-grid`);
    if (!grid) return;

    const formData = new FormData();
    formData.append("action", "resol_load_more_posts");
    formData.append("nonce", RESOL_LOAD_MORE.nonce);
    formData.append("offset", offset);
    formData.append("per_page", perPage);
    formData.append("term_id", termId);
    formData.append("root_key", rootKey);

    try {
      const res = await fetch(RESOL_LOAD_MORE.ajax_url, {
        method: "POST",
        body: formData,
      });

      const data = await res.json();
      if (!data.success) {
        console.warn("Load more error", data);
        return;
      }

      const html = data.data.html || "";
      const hasMore = data.data.hasMore;

      if (html.trim() !== "") {
        const temp = document.createElement("div");
        temp.innerHTML = html;

        [...temp.children].forEach((child) => {
          grid.appendChild(child);
        });

        btn.dataset.offset = offset + perPage;
      }

      if (!hasMore || html.trim() === "") {
        btn.style.display = "none";
      }
    } catch (err) {
      console.error("AJAX failed", err);
    } finally {
      isLoading = false;
    }
  });
});
