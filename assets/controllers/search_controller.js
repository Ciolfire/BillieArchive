import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller
{
  static targets = [
    "item",
    "query",
    "filter"
  ];
  static values = {
    type: String,
    filters: Object
  }

  connect()
  {
    console.log("v1.2");
  }

  switchFilter(event)
  {
    let filters = this.filtersValue;
    let name = event.params.filter;
    let type = event.params.type;
    
    if (typeof filters[type] === "undefined") {
      let newFilter = {};
      newFilter[name] = true;
      filters[type] = newFilter;
      event.currentTarget.classList.add("active");
    } else {
      if (typeof filters[type][name] === "undefined" || filters[type][name] === false) {
        filters[type][name] = true;
        event.currentTarget.classList.add("active");
      } else {
        filters[type][name] = undefined;
        event.currentTarget.classList.remove("active");
      }
    }
    this.filtersValue = filters;
    // All filters ok, we display the updated results
    this.getResults();
  }

  // Check if there is any filter set, if yes, return true
  checkFilters()
  {
    let filters = this.filtersValue;

    for (const [key, values] of Object.entries(filters)) {
      for (const value in values) {
        if (filters[key][value] === true) {

          return true;
        }
      }
    }

    return false;
  }

  // Display the items, depending if the match the search criterias
  getResults()
  {
    let hasFilter = this.checkFilters();
    let filters = this.filtersValue;
    
    this.itemTargets.forEach(item => {
      console.debug(item);
      if (!hasFilter) {
        item.classList.remove("collapse");
      } else {
        let isValid;
        for (const [key, values] of Object.entries(filters)) {
          if (Object.keys(values).length > 0) {
            console.debug(Object.keys(values));
            isValid = false;
            for (const value in values) {
              if (filters[key][value] === true && item.dataset[key] === value) {
                console.debug(key);
                console.debug(value);
                console.debug(item.dataset[key]);
                isValid = true;
                break;
              }
            }
            if (isValid !== true) {
              console.debug("not valid");
              isValid = false;
              break;
            }
          }
        }
        if (isValid) {
          item.classList.remove("collapse");
        } else {
          item.classList.add("collapse");
        }
      }
    });
  }
}