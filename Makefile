REPOSITORY=https://github.com/seishin77/wotan.git
REPONAME=`git remote`
BRANCH=`git branch|grep '*'|sed -e 's/^\s*\*\s*//g'`

EFLAGS="-e"

help:
	@echo $(EFLAGS) "Available commands :"
	@echo $(EFLAGS) "\tTo commit all :"
	@echo $(EFLAGS) "\t\tmake [commit-all | cia]\n"

	@echo $(EFLAGS) "\tTo commit :"
	@echo $(EFLAGS) "\t\tmake [commit | ci]\n"

	@echo $(EFLAGS) "\tTo pull a branch (active branch by default) :"
	@echo $(EFLAGS) "\t\tmake pull [BRANCH=<branch>]\n"

	@echo $(EFLAGS) "\tTo push :"
	@echo $(EFLAGS) "\t\tmake push\n"

	@echo $(EFLAGS) "\tTo fetch :"
	@echo $(EFLAGS) "\t\tmake fetch\n"

	@echo $(EFLAGS) "\tTo merge :"
	@echo $(EFLAGS) "\t\tmake merge\n"

	@echo $(EFLAGS) "\tTo diff :"
	@echo $(EFLAGS) "\t\tmake diff\n"

	@echo $(EFLAGS) "\tTo add a file :"
	@echo $(EFLAGS) "\t\tmake add FILE=<file>\n"

	@echo $(EFLAGS) "\tTo get the status :"
	@echo $(EFLAGS) "\t\tmake [status | st]\n"

	@echo $(EFLAGS) "\tTo get the branches :"
	@echo $(EFLAGS) "\t\tmake [branch | br]\n"


	@echo $(EFLAGS) "\nVariables:"
	@echo $(EFLAGS) "\tDEFAULT BRANCH\t\t= '$(BRANCH)'"
	@echo $(EFLAGS) "\tDEFAULT REPOSITORY NAME\t= '$(REPONAME)'"
	@echo $(EFLAGS) "\tDEFAULT REPOSITORY\t= '$(REPOSITORY)'"


diff:
	@git diff

add:
	@git add $(FILE)

merge:
	@git merge

push:
	@git push origin $(BRANCH)

pull:
	@git pull $(REPOSITORY) $(BRANCH)

checkout:
	@git checkout

commit:
	@git commit

commit-all:
	@git commit -a

fetch:
	@git fetch $(REPONAME)

status:
	@git status

branch:
	@git branch

# Alias
ci: commit
cia: commit-all
co: checkout
st: status
br: branch


.PHONY: clean mrproper printvars

clean: ;
#	@rm -rf *.o

mrproper: clean
#	@rm -rf $(EXEC)

printvars:
	@$(foreach V,$(sort $(.VARIABLES)), \
		$(if $(filter-out environment% default automatic, $(origin $V)),\
			$(info $V=$(value $V)) \
		) \
	)
