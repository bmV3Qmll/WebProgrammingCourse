function onlyOne(checkbox) {
	var checkboxes = document.getElementsByName(checkbox.name)
	checkboxes.forEach((item) => {
		if (item !== checkbox) item.checked = false
	})
}