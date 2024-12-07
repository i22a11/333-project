export default class StatsCard extends HTMLElement {
  /**
   * @param {Object} options
   * @param {string} options.icon - Icon class name
   * @param {string} options.title - Card title
   * @param {number|string} options.value - Card value
   * @param {string} options.color - Color theme for the card
   */
  constructor(options) {
    super();
    this.icon = options.icon;
    this.title = options.title;
    this.value = options.value;
    this.color = options.color;
  }

  connectedCallback() {
    this.innerHTML = this.render();
  }

  render() {
    const colorClasses = {
      blue: 'bg-blue-100 text-blue-500',
      green: 'bg-green-100 text-green-500',
      yellow: 'bg-yellow-100 text-yellow-500',
      purple: 'bg-purple-100 text-purple-500'
    };

    // @ts-ignore
    const colorClass = colorClasses[this.color] || colorClasses.blue;

    return `
      <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center">
            <div class="rounded-full w-12 h-12 flex items-center justify-center ${colorClass}">
              <i class="${this.icon} text-xl"></i>
            </div>
            <h3 class="ml-4 text-lg font-semibold text-gray-700">${this.title}</h3>
          </div>
        </div>
        <div class="text-3xl font-bold text-gray-900">${this.value}</div>
      </div>
    `;
  }
}

customElements.define("stats-card", StatsCard);
