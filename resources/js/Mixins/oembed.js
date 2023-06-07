export default class {

    constructor(params = {}) {
        const buildParams = {
            youtube: {
                type: "iframe",
                attributes: {
                    src: "https://www.youtube.com/embed",
                    class: "w-full aspect-video",
                    frameborder: "0",
                    allow: "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share",
                    allowfullscreen: true
                }
            }
        };
        this.buildParams = {...buildParams, ...params};
    }

    render() {
        const mediaElements = document.querySelectorAll("oembed");
        mediaElements.forEach(element => {
            this.build(element);
        });
    }

    build(element) {
        if (/youtube\.com|youtu\.be/.test(element.attributes.url.value)) {
            const newElement = document.createElement(this.buildParams.youtube.type);
            Object.entries(this.buildParams.youtube.attributes).forEach(attrs => {
                const [key, value] = attrs;
                if(key === 'src'){
                    let result = /[^\/=][._\-0-9a-zA-Z]+$/.exec(element.attributes.url.value);
                    newElement.setAttribute(key, `${value}/${result[0]}`);
                } else {
                    newElement.setAttribute(key, `${value}`);
                }
            });

            element.replaceWith(newElement);
        }
    }
}
