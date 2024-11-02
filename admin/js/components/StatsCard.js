class StatsCard {
  constructor(icon, title, value, color) {
    this.icon = icon;
    this.title = title;
    this.value = value;
    this.color = color;
  }

  render() {
    return `
            <div class="rounded-lg bg-white p-6 shadow">
                <div class="flex items-center">
                    <div class="flex items-center justify-center rounded-full bg-${this.color}-500 p-3 w-10 h-10">
                        <i class="${this.icon} h-8 text-white mt-4"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600">${this.title}</h2>
                        <p class="text-2xl font-semibold text-gray-800">${this.value}</p>
                    </div>
                </div>
            </div>
        `;
  }
}

customElements.define("stats-card", StatsCard);