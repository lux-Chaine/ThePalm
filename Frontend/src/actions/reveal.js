/**
 * Svelte action: reveal
 * Adds class "in" when element enters the viewport, removes it when it leaves.
 * Usage: <div use:reveal> or <div use:reveal={{ threshold: 0.15, delay: 200 }}>
 */
export function reveal(node, options = {}) {
  const { threshold = 0.12, delay = 0 } = options;

  // Start hidden
  node.style.opacity = '0';
  node.style.transform = 'translateY(28px)';
  node.style.transition = `opacity 0.75s ease ${delay}ms, transform 0.75s ease ${delay}ms`;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          node.classList.add('in');
          node.style.opacity = '1';
          node.style.transform = 'translateY(0)';
        } else {
          node.classList.remove('in');
          node.style.opacity = '0';
          node.style.transform = 'translateY(28px)';
        }
      });
    },
    { threshold }
  );

  observer.observe(node);

  return {
    destroy() {
      observer.disconnect();
    },
    update(newOptions = {}) {
      const { delay: newDelay = 0 } = newOptions;
      node.style.transition = `opacity 0.75s ease ${newDelay}ms, transform 0.75s ease ${newDelay}ms`;
    }
  };
}
