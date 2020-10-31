using System;
using System.Collections.Generic;
using Microsoft.AspNetCore.Http;
using Newtonsoft.Json;
using Newtonsoft.Json.Serialization;

namespace Engrisk.Helper
{
    public static class Extension
    {
        public static void AddPaginationHeader(this HttpResponse response, int currentPage, int pageSize, int totalItems, int totalPages)
        {
            var paginationHeader = new PaginationHeader(currentPage, pageSize, totalItems, totalPages);
            var camelPropertyName = new JsonSerializerSettings();
            camelPropertyName.ContractResolver = new CamelCasePropertyNamesContractResolver();
            response.Headers.Append("Pagination", JsonConvert.SerializeObject(paginationHeader, camelPropertyName));
            response.Headers.Append("Access-Control-Expose-Headers", "Pagination");
        }
        public static bool CompareDate(this DateTime date, DateTime inputDate)
        {
            if (date.Year != inputDate.Year || date.Month != inputDate.Month || date.Day != inputDate.Day)
            {
                return false;
            }
            return true;
        }
        public static bool CompareProperties<T>(this T subject, Dictionary<dynamic, dynamic> properties) where T : class
        {
            int samePropertiesCount = 0;
            foreach (var property in subject.GetType().GetProperties())
            {
                if(properties.CompareKey(property.Name))
                {
                    if (property.GetValue(subject).Equals(properties[property.Name]))
                {
                    samePropertiesCount++;
                }
                }    
            }
            if (samePropertiesCount == properties.Count)
            {
                return true;
            }
            return false;
        }
        public static bool CompareKey(this Dictionary<dynamic,dynamic> properties, dynamic key)
        {
            foreach(var propKey in properties.Keys)
            {
                if(propKey.Equals(key))
                {
                    return true;
                }
            }
            return false;
        }
        public static bool ExistsValue<T>(this T subject, dynamic value) where T : class
        {
            foreach (var property in subject.GetType().GetProperties())
            {
                if (property.GetValue(subject).Equals(value))
                {
                    return true;
                }
            }
            return false;
        }
        public static int CalculateAge(this DateTime dob)
        {
            var age = DateTime.Now.Year - dob.Year;
            if (dob.AddYears(age) > DateTime.Now)
            {
                age--;
            }
            return age;
        }
        public static int MinusDate(this DateTime startDate, DateTime endDate)
        {
            var hours = endDate.Date.Hour - startDate.Date.Hour;
            var minutes = endDate.Date.Minute - startDate.Date.Minute;
            return hours*60 + minutes;
        }
    }
}