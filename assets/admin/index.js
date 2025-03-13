/**
 * TOP page setting
 */
((window, document) => {
  const section = document.getElementsByClassName('custom-setting')[0];
  if (!section) return;
  const sectionTitle = section.getElementsByTagName('h2');

  for (let i = 0; i < sectionTitle.length; i++) {
    const table = sectionTitle[i].nextElementSibling;
    if (!table) return;
    const checkbox = table.getElementsByClassName('hidden_checkbox')[0];
    if (!checkbox) return;
    const checked = checkbox.checked;
    checkbox.parentNode.parentNode.style.display = 'none';
    if (checked) {
      sectionTitle[i].classList.add('open');
      table.style.display = 'table';
    }
    sectionTitle[i].addEventListener('click', () => {
      if (window.getComputedStyle(table).display === 'none') {
        sectionTitle[i].classList.add('open');
        table.style.display = 'table';
        checkbox.checked = true;
      } else {
        sectionTitle[i].classList.remove('open');
        table.style.display = ' none';
        checkbox.checked = false;
      }
    });
  }

  window.addEventListener("load", () => {
    const notice = section.getElementsByClassName("notice")[0];
    if (!notice) return;
    setTimeout(() => notice.style.opacity = "0", 4500);
  });
})(window, document);
((window, document) => {
  // section1 select box
  // const section1SelectBox = document.querySelector('[name="section_1_post_type"]');
  // if (!section1SelectBox) return;
  // const selectPost = section1SelectBox.parentNode?.parentNode?.nextElementSibling;
  // selectPost.style.display = "none";
  // if (!selectPost) return;
  // section1SelectBox.addEventListener("change", () => {
  //   if (section1SelectBox.value === "custom") {
  //     selectPost.style.display = "table-row";
  //   } else {
  //     selectPost.style.display = "none";
  //   }
  // });
})(window, document);
