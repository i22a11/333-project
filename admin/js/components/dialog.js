// @ts-nocheck
export default class CustomDialog extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({ mode: "open" });
    this.isOpen = false;
  }

  connectedCallback() {
    this.render();
    this.setupEvents();
  }

  render() {
    const title = this.getAttribute("title") || "Dialog Title";
    const description = this.getAttribute("description") || "";

    this.shadowRoot.innerHTML = `
      <style>
        :host {
          --primary-color: #2563eb;
          --primary-hover: #1d4ed8;
        }

        .dialog-overlay {
          position: fixed;
          inset: 0;
          background-color: rgba(0, 0, 0, 0.5);
          display: flex;
          align-items: center;
          justify-content: center;
          padding: 1rem;
          opacity: 0;
          visibility: hidden;
          transition: opacity 0.3s, visibility 0.3s;
          z-index: 50;
        }

        .dialog-overlay.active {
          opacity: 1;
          visibility: visible;
        }

        .dialog-content {
          background-color: white;
          border-radius: 0.5rem;
          box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
          width: 100%;
          max-width: 28rem;
          position: relative;
          transform: scale(0.9);
          transition: transform 0.3s;
        }

        .dialog-overlay.active .dialog-content {
          transform: scale(1);
        }

        .dialog-close {
          position: absolute;
          right: 1rem;
          top: 1rem;
          background: none;
          border: none;
          cursor: pointer;
          opacity: 0.7;
          transition: opacity 0.2s;
        }

        .dialog-close:hover {
          opacity: 1;
        }

        .dialog-header {
          padding: 1.5rem;
          text-align: center;
        }

        .dialog-title {
          margin: 0;
          font-size: 1.125rem;
          font-weight: 600;
        }

        .dialog-description {
          margin: 0.5rem 0 0;
          font-size: 0.875rem;
          color: #6b7280;
        }

        .dialog-body {
          padding: 0 1.5rem;
        }

        .dialog-footer {
          display: flex;
          flex-direction: column-reverse;
          padding: 1.5rem;
          gap: 0.5rem;
        }

        .button {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          border-radius: 0.375rem;
          font-size: 0.875rem;
          font-weight: 500;
          padding: 0.5rem 1rem;
          transition: background-color 0.2s, color 0.2s;
          cursor: pointer;
        }

        .button-secondary {
          background-color: white;
          border: 1px solid #d1d5db;
          color: #374151;
        }

        .button-secondary:hover {
          background-color: #f3f4f6;
        }

        .button-primary {
          background-color: var(--primary-color);
          border: 1px solid var(--primary-color);
          color: white;
        }

        .button-primary:hover {
          background-color: var(--primary-hover);
        }

        @media (min-width: 640px) {
          .dialog-header {
            text-align: left;
          }

          .dialog-footer {
            flex-direction: row;
            justify-content: flex-end;
          }
        }
      </style>
      <div class="dialog-overlay" role="dialog" aria-modal="true" aria-labelledby="dialog-title">
        <div class="dialog-content">
          <button class="dialog-close" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
          <div class="dialog-header">
            <h2 id="dialog-title" class="dialog-title">${title}</h2>
            ${description ? `<p class="dialog-description">${description}</p>` : ""}
          </div>
          <div class="dialog-body">
            <slot></slot>
          </div>
          <div class="dialog-footer">
            <slot name="cancel"></slot>
            <slot name="confirm"></slot>
          </div>
        </div>
      </div>
    `;
  }

  setupEvents() {
    const overlay = this.shadowRoot.querySelector(".dialog-overlay");
    const closeBtn = this.shadowRoot.querySelector(".dialog-close");
    const content = this.shadowRoot.querySelector(".dialog-content");

    if (closeBtn) {
      closeBtn.addEventListener("click", () => this.close());
    }

    if (overlay) {
      overlay.addEventListener("click", (event) => {
        if (event.target === overlay) {
          this.close();
        }
      });
    }

    if (content) {
      content.addEventListener("click", (event) => {
        event.stopPropagation();
      });
    }

    // Handle cancel button slot
    const cancelSlot = this.shadowRoot.querySelector('slot[name="cancel"]');
    if (cancelSlot) {
      cancelSlot.addEventListener("slotchange", (e) => {
        const elements = e.target.assignedElements();
        if (elements.length > 0) {
          const cancelBtn = elements[0];
          cancelBtn.classList.add("button", "button-secondary");
          cancelBtn.addEventListener("click", () => this.close());
        }
      });
    }

    // Handle confirm button slot
    const confirmSlot = this.shadowRoot.querySelector('slot[name="confirm"]');
    if (confirmSlot) {
      confirmSlot.addEventListener("slotchange", (e) => {
        const elements = e.target.assignedElements();
        if (elements.length > 0) {
          const confirmBtn = elements[0];
          confirmBtn.classList.add("button", "button-primary");
        }
      });
    }
  }

  open() {
    const overlay = this.shadowRoot.querySelector(".dialog-overlay");
    if (overlay) {
      overlay.classList.add("active");
      this.isOpen = true;
    }
  }

  close() {
    const overlay = this.shadowRoot.querySelector(".dialog-overlay");
    if (overlay) {
      overlay.classList.remove("active");
      this.isOpen = false;
      this.dispatchEvent(new CustomEvent("dialog-close"));
    }
  }

  toggle() {
    if (this.isOpen) {
      this.close();
    } else {
      this.open();
    }
  }
}

customElements.define("custom-dialog", CustomDialog);
