scss := $(wildcard src/css/**/*.scss)
css  := $(patsubst src/%,static/%,$(scss:scss=css))

static: css hugo public/resume/resume.pdf

css: $(css)

static/%.css: src/%.scss
	sass --style compressed $< $@

hugo:
	@hugo

public/resume/resume.pdf: $(css) public/resume/index.html
	wkhtmltopdf \
		-s Letter \
		--print-media-type \
		--footer-center "[title]" \
		--footer-left "[isodate]" \
		--footer-right "Page [page]/[toPage]" \
		--footer-font-size 6 \
		--footer-font-name "Cardo" \
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
	git push

