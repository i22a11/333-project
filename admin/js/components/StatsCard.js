export default class StatsCard extends HTMLElement {
  /**
   *
   * @param {string} icon
   * @param {string} title
   * @param {string} value
   * @param {string} color
   */
  constructor(icon, title, value, color) {
    super();
    this.icon = icon;
    this.title = title;
    this.value = value;
    this.color = color;
  }

  getIconColorClass() {
    switch (this.color) {
      case 'blue':
        return 'bg-blue-50 text-blue-600';
      case 'green':
        return 'bg-green-50 text-green-600';
      case 'yellow':
        return 'bg-yellow-50 text-yellow-600';
      case 'indigo':
        return 'bg-indigo-50 text-indigo-600';
      default:
        return 'bg-gray-50 text-gray-600';
    }
  }

  getValueColorClass() {
    switch (this.color) {
      case 'blue':
        return 'text-blue-600';
      case 'green':
        return 'text-green-600';
      case 'yellow':
        return 'text-yellow-600';
      case 'indigo':
        return 'text-indigo-600';
      default:
        return 'text-gray-600';
    }
  }

  render() {
    return `
            <div class="relative overflow-hidden rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg ${this.getIconColorClass()}">
                            <!-- <i class="${this.icon} text-xl"></i> -->
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">${this.title}</p>
                            <h3 class="mt-1 text-2xl font-bold ${this.getValueColorClass()}">${this.value}</h3>
                        </div>
                    </div>
                    <div class="absolute bottom-0 right-0 h-24 w-24 transform translate-x-6 translate-y-6">
                        <div class="absolute inset-0 ${this.getIconColorClass()} opacity-10">
                            <i class="${this.icon} text-6xl transform -rotate-12"></i>
                        </div>
                    </div>
                </div>
            </div>
        `;
  }
}

customElements.define("stats-card", StatsCard);
