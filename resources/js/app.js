import './bootstrap';

import Alpine from 'alpinejs';

    Alpine.data('queueTimer', (joinedAt) => ({
        joinedAt: joinedAt * 1000,
        timeString: '',
        timer: null,

        init() {
            this.update();
            this.timer = setInterval(() => this.update(), 1000);
        },

        destroy() {
            clearInterval(this.timer);
        },

        update() {
            // calculate the hours/mins/secs
            const timeDiff = Math.floor((Date.now() - this.joinedAt) / 1000);
            const hours = Math.floor(timeDiff / 3600);
            const minutes = Math.floor((timeDiff % 3600) / 60);
            const seconds = Math.floor(timeDiff % 60);

            // the seperated time strings before joining
            let timeStrings = [];
            if (hours > 0) {
                timeStrings.push(`${hours} hour${hours===1 ? '':'s'}`);
            }
            if (minutes > 0) {
                timeStrings.push(`${minutes} minute${minutes===1 ? '':'s'}`);
            }
            timeStrings.push(`${seconds} second${seconds===1 ? '':'s'}`);

            this.timeString = timeStrings.join(' ');
        }
    }));
window.Alpine = Alpine;
Alpine.start();
