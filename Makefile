scss := $(wildcard src/css/**/*.scss)
css  := $(patsubst src/%,static/%,$(scss:scss=css))

static: css hugo public/resume/resume.pdf

css: $(css)

static/%.css: src/%.scss
	sass $< $@

hugo:
	@hugo

public/resume/resume.pdf: $(css) public/resume/index.html
	wkhtmltopdf \
		-s Letter \
		--print-media-type \
		--footer-right "Page [page]/[toPage]" \
		--footer-font-size 8 \
		--footer-font-name "Hoefler Text" \
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
	git push origin master

