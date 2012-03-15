BUILD=`pwd`/build
WIKI_PAGES=items.md commands.md
BOTLIFE_PATH=`pwd`/BotLife/src


.PHONY: $(addsuffix .php,$(WIKI_PAGES)) 
%.md : %.md.php
	scripts/build/$< $(BUILD)/$@ $(BOTLIFE_PATH)

wiki : $(WIKI_PAGES)

clean :
	$(RM) $(addprefix $(BUILD)/,$(WIKI_PAGES))
