mod_location.la: mod_location.slo
	$(SH_LINK) -rpath $(libexecdir) -module -avoid-version  mod_location.lo
DISTCLEAN_TARGETS = modules.mk
shared =  mod_location.la
