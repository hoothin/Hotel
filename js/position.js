function findArray(array, feature, all = true) {
    for (let index in array) {
        let cur = array[index];
        if (feature instanceof Object) {
            let allRight = true;
            for (let key in feature) {
                let value = feature[key];
                if (cur[key] == value && !all) return index;
                if (all && cur[key] != value) {
                    allRight = false;
                    break;
                }
            }
            if (allRight) return index;
        } else {
            if (cur == feature) {
                return index;
            }
        }
    }
    return -1;
}

function getCityStr(id, func){
	$.getJSON("static/pos.json", function (data){
		$.each(data, function (i,province){
			var index=findArray(province.city, {city_id: id});
			if(index!=-1){
				func(province.city[index].name);
				return false;
			}
		})
    })
}

function getProvinceStr(id, func){
	$.getJSON("static/pos.json", function (data){
		var index=findArray(data, {province_id: id});
		if(index!=-1){
			func(data[index].province);
		}
    })
}

function getCityID(str, func){
	$.getJSON("static/pos.json", function (data){
		$.each(data, function (i,province){
			var index=findArray(province.city, {name: str});
			if(index!=-1){
				func(province.city[index]);
				return false;
			}
		})
    })
}

function getProvinceID(str, func){
	$.getJSON("static/pos.json", function (data){
		var index=findArray(data, {province: str});
		if(index!=-1){
			func(data[index]);
		}
    })
}