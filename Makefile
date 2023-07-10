scss := $(wildcard src/css/**/*.scss)
css  := $(patsubst src/%,static/%,$(scss:scss=css))

CHROME = "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"

.PHONY: css hugo
static: css hugo public/resume/resume.pdf

css: $(css)

static/%.css: src/%.scss
	sassc --style compressed $< $@

hugo:
	@hugo

public/resume/resume.pdf: $(css) content/resume.md
	$(CHROME) public/resume/index.html
	echo wkhtmltopdf \
		-s Letter \
		--print-media-type \
		--footer-center "[title]" \
		--footer-left "[isodate]" \
		--footer-right "Page [page]/[toPage]" \
		--footer-font-size 6 \
		--footer-font-name "Cardo" \
		--footer-spacing 19 \
		--margin-bottom 25mm \
		--no-outline \
		--quiet \
		public/resume/index.html public/resume/resume.pdf

watch: watchsass watchhugo

watchsass:
	sass --watch src/css:static/css

watchhugo:
	hugo server -D --watch

.ONESHELL:
deploy: static
	cd public; \
	git add -A; \
	git commit -m "rebuilding site $$(date)";\
	git push origin HEAD:gh-pages

