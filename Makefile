BUILD=`pwd`/build
WIKI_PAGES=items.md commands.md


.PHONY: $(addsuffix .php,$(WIKI_PAGES)) 
%.md : %.md.php
	scripts/build/$< $(BUILD)/$@

wiki : $(WIKI_PAGES)

clean :
	$(RM) $(addprefix $(BUILD)/,$(WIKI_PAGES))
