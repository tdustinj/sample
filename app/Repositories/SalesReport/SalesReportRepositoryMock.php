<?php

namespace App\Repositories\SalesReport;

use App\Repositories\SalesReport\SalesReportRepositoryContract;
use Illuminate\Support\Collection;

class SalesReportRepositoryMock implements SalesReportRepositoryContract
{
    public function getTotalsByInvoiceType(): Collection
    {
        $json = $this->_getTotalsByInvoiceTypeMockJson();
        $results = collect(json_decode($json, true));
        return $results;
    }

    public function getAllOpenOrders(): Collection
    {
        $json = $this->_getAllOpenOrdersMockJson();
        $results = collect(json_decode($json, true));
        return $results;
    }

    public function getOpenOrdersByPartner(): Collection
    {
        $json = $this->_getOpenOrdersByPartnerMockJson();
        $results = collect(json_decode($json, true));
        return $results;
    }

    public function getOpenOrdersByOrderStatus(): Collection
    {
        $json = $this->_getOpenOrdersByOrderStatusMockJson();
        $results = collect(json_decode($json, true));
        return $results;
    }

    public function getOpenNonPartnerOrders(): Collection
    {
        $json = $this->_getOpenNonPartnerOrdersMockJson();
        $results = collect(json_decode($json, true));
        return $results;
    }

    public function getOpenInstalls(): Collection
    {
        $json = $this->_getOpenInstallsMockJson();
        $results = collect(json_decode($json, true));
        return $results;
    }

    protected function _getTotalsByInvoiceTypeMockJson()
    {
        return '
        [
            {
                "invoiceType": "B2B Local Resale",
                "thisYearsRevenue": 123,
                "lastYearsRevenue": 234
            },
            {
                "invoiceType": "B2B Out Of State",
                "thisYearsRevenue": 111,
                "lastYearsRevenue": 121
            },
            {
                "invoiceType": "B2B In State",
                "thisYearsRevenue": 321,
                "lastYearsRevenue": 300
            }
        ]';
    }

    protected function _getAllOpenOrdersMockJson()
    {
        return '
        [
            {
                "Invoice": "270056",
                "Invoice Type": "B2B Out Of State",
                "Sales Person": "Richard",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-05-16",
                "Schedule Date": "0000-00-00",
                "Total": "30,549.75"
            },
            {
                "Invoice": "271984",
                "Invoice Type": "B2B Out Of State",
                "Sales Person": "Richard",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-05-28",
                "Schedule Date": "0000-00-00",
                "Total": "25,983.21"
            },
            {
                "Invoice": "271985",
                "Invoice Type": "B2B Out Of State",
                "Sales Person": "Richard",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-05-28",
                "Schedule Date": "0000-00-00",
                "Total": "17,481.55"
            },
            {
                "Invoice": "272286",
                "Invoice Type": "B2B Out Of State",
                "Sales Person": "Richard",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-05-29",
                "Schedule Date": "0000-00-00",
                "Total": "101,500.00"
            },
            {
                "Invoice": "273693",
                "Invoice Type": "B2B Out Of State",
                "Sales Person": "House",
                "Customer Name": ",",
                "Invoice Date": "2019-06-07",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "273973",
                "Invoice Type": "B2B Out Of State",
                "Sales Person": "Richard",
                "Customer Name": ",",
                "Invoice Date": "2019-06-10",
                "Schedule Date": "0000-00-00",
                "Total": "3,242.93"
            },
            {
                "Invoice": "274001",
                "Invoice Type": "B2B Out Of State",
                "Sales Person": "Richard",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-06-10",
                "Schedule Date": "0000-00-00",
                "Total": "7,911.81"
            },
            {
                "Invoice": "274002",
                "Invoice Type": "B2B Out Of State",
                "Sales Person": "Richard",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-06-10",
                "Schedule Date": "0000-00-00",
                "Total": "14,696.30"
            },
            {
                "Invoice": "274167",
                "Invoice Type": "B2B Out Of State",
                "Sales Person": "Richard",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-06-11",
                "Schedule Date": "0000-00-00",
                "Total": "6,208.99"
            },
            {
                "Invoice": "274207",
                "Invoice Type": "B2B Out Of State",
                "Sales Person": "Richard",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-06-11",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "250007",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "House",
                "Customer Name": "WHITE, CHRIS",
                "Invoice Date": "2018-12-29",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "250201",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "House",
                "Customer Name": "WHITE, CHRIS",
                "Invoice Date": "2018-12-31",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "250549",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Steve",
                "Customer Name": "TACKETT, BRAD",
                "Invoice Date": "2019-01-04",
                "Schedule Date": "0000-00-00",
                "Total": "1,496.23"
            },
            {
                "Invoice": "264372",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Matt L",
                "Customer Name": "ESCAMILLO, TONY",
                "Invoice Date": "2019-04-16",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "265391",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Miles",
                "Customer Name": "PLOEN, DON",
                "Invoice Date": "2019-04-21",
                "Schedule Date": "0000-00-00",
                "Total": "-702.65"
            },
            {
                "Invoice": "266423",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Miles",
                "Customer Name": "FELDMAN, MARK",
                "Invoice Date": "2019-04-25",
                "Schedule Date": "0000-00-00",
                "Total": "-107.02"
            },
            {
                "Invoice": "268023",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Matt L",
                "Customer Name": "MARIANO, JOHN",
                "Invoice Date": "2019-05-03",
                "Schedule Date": "0000-00-00",
                "Total": "-215.50"
            },
            {
                "Invoice": "268425",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Nate",
                "Customer Name": "NATALIE, BYRD",
                "Invoice Date": "2019-05-06",
                "Schedule Date": "0000-00-00",
                "Total": "75.67"
            },
            {
                "Invoice": "268684",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Tom",
                "Customer Name": "THOMPSON, BRAD",
                "Invoice Date": "2019-05-07",
                "Schedule Date": "0000-00-00",
                "Total": "27.02"
            },
            {
                "Invoice": "270493",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "House",
                "Customer Name": "BOURNE, LOGAN",
                "Invoice Date": "2019-05-19",
                "Schedule Date": "0000-00-00",
                "Total": "108.10"
            },
            {
                "Invoice": "270772",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Bob",
                "Customer Name": "MOXY HOTEL, JOSE LUIS",
                "Invoice Date": "2019-05-21",
                "Schedule Date": "0000-00-00",
                "Total": "254.45"
            },
            {
                "Invoice": "271866",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Matt L",
                "Customer Name": "LOWE, MATT",
                "Invoice Date": "2019-05-27",
                "Schedule Date": "0000-00-00",
                "Total": "68.00"
            },
            {
                "Invoice": "271870",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "House",
                "Customer Name": "LOWE, MATTHEW",
                "Invoice Date": "2019-05-27",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "272367",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Miles",
                "Customer Name": "FARRELL, BART",
                "Invoice Date": "2019-05-29",
                "Schedule Date": "0000-00-00",
                "Total": "1,581.49"
            },
            {
                "Invoice": "272824",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Matt L",
                "Customer Name": "TEGEN, THIERRY",
                "Invoice Date": "2019-06-02",
                "Schedule Date": "0000-00-00",
                "Total": "4,253.74"
            },
            {
                "Invoice": "272933",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Miles",
                "Customer Name": "WEBER, BERNIE",
                "Invoice Date": "2019-06-03",
                "Schedule Date": "0000-00-00",
                "Total": "1,567.44"
            },
            {
                "Invoice": "273829",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Matt L",
                "Customer Name": "SLOAN, GERMAINE",
                "Invoice Date": "2019-06-09",
                "Schedule Date": "0000-00-00",
                "Total": "541.04"
            },
            {
                "Invoice": "274007",
                "Invoice Type": "Carry Out Sale",
                "Sales Person": "Richard",
                "Customer Name": "GHABBANI, DANIEL",
                "Invoice Date": "2019-06-10",
                "Schedule Date": "0000-00-00",
                "Total": "156.20"
            },
            {
                "Invoice": "274192",
                "Invoice Type": "Chat Sale Out Of State",
                "Sales Person": "Nikki",
                "Customer Name": "URBANCIC, CYRIL",
                "Invoice Date": "2019-06-11",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "261231",
                "Invoice Type": "Delivery Sale",
                "Sales Person": "Ethan",
                "Customer Name": "KOCH, KEN",
                "Invoice Date": "2019-03-27",
                "Schedule Date": "2019-06-06",
                "Total": "3,489.56"
            },
            {
                "Invoice": "266876",
                "Invoice Type": "Delivery Sale",
                "Sales Person": "Matt L",
                "Customer Name": "ZENDEJAS, DANIEL JR",
                "Invoice Date": "2019-04-28",
                "Schedule Date": "2019-04-29",
                "Total": "4,049.69"
            },
            {
                "Invoice": "272406",
                "Invoice Type": "Delivery Sale",
                "Sales Person": "sears",
                "Customer Name": "RODRIGUEZ, GABRIEL",
                "Invoice Date": "2019-05-30",
                "Schedule Date": "2019-06-04",
                "Total": "1,068.98"
            },
            {
                "Invoice": "272831",
                "Invoice Type": "Delivery Sale",
                "Sales Person": "Antonio",
                "Customer Name": "IWATAKE, LAURIE",
                "Invoice Date": "2019-06-02",
                "Schedule Date": "2019-06-04",
                "Total": "2,170.60"
            },
            {
                "Invoice": "272946",
                "Invoice Type": "Delivery Sale",
                "Sales Person": "Amazon",
                "Customer Name": "CRANMER, RUSSELL",
                "Invoice Date": "2019-06-03",
                "Schedule Date": "2019-06-11",
                "Total": "3,251.60"
            },
            {
                "Invoice": "273482",
                "Invoice Type": "Delivery Sale",
                "Sales Person": "Ed",
                "Customer Name": "HISAMOTO, ED",
                "Invoice Date": "2019-06-06",
                "Schedule Date": "2019-06-18",
                "Total": "555.86"
            },
            {
                "Invoice": "273750",
                "Invoice Type": "Delivery Sale",
                "Sales Person": "Richard",
                "Customer Name": ", MARK",
                "Invoice Date": "2019-06-08",
                "Schedule Date": "0000-00-00",
                "Total": "4,107.80"
            },
            {
                "Invoice": "273898",
                "Invoice Type": "Delivery Sale",
                "Sales Person": "Matt L",
                "Customer Name": "SCHMITT, AUSTIN",
                "Invoice Date": "2019-06-10",
                "Schedule Date": "0000-00-00",
                "Total": "405.38"
            },
            {
                "Invoice": "273919",
                "Invoice Type": "Delivery Sale",
                "Sales Person": "Ed",
                "Customer Name": "HELIOTI, MARIA",
                "Invoice Date": "2019-06-10",
                "Schedule Date": "2019-06-11",
                "Total": "1,817.38"
            },
            {
                "Invoice": "273947",
                "Invoice Type": "Delivery Sale",
                "Sales Person": "House",
                "Customer Name": "HANNA, HANK",
                "Invoice Date": "2019-06-10",
                "Schedule Date": "2019-06-12",
                "Total": "2,153.84"
            },
            {
                "Invoice": "273998",
                "Invoice Type": "Delivery Sale",
                "Sales Person": "Richard",
                "Customer Name": "ANDING, RYAN",
                "Invoice Date": "2019-06-10",
                "Schedule Date": "0000-00-00",
                "Total": "3,904.94"
            },
            {
                "Invoice": "263563",
                "Invoice Type": "Marketplace In State",
                "Sales Person": "House",
                "Customer Name": "ESCAMILLO, TONY",
                "Invoice Date": "2019-04-12",
                "Schedule Date": "0000-00-00",
                "Total": "81.08"
            },
            {
                "Invoice": "271240",
                "Invoice Type": "Marketplace In State",
                "Sales Person": "Antonio",
                "Customer Name": "WARREN, GIL",
                "Invoice Date": "2019-05-23",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "273222",
                "Invoice Type": "Marketplace In State",
                "Sales Person": "Amazon",
                "Customer Name": "GREEN, LARRY M",
                "Invoice Date": "2019-06-04",
                "Schedule Date": "2019-06-13",
                "Total": "1,068.98"
            },
            {
                "Invoice": "273428",
                "Invoice Type": "Marketplace In State",
                "Sales Person": "Amazon",
                "Customer Name": "FASSETT, HOWARD",
                "Invoice Date": "2019-06-06",
                "Schedule Date": "2019-06-13",
                "Total": "3,076.34"
            },
            {
                "Invoice": "273570",
                "Invoice Type": "Marketplace In State",
                "Sales Person": "Antonio",
                "Customer Name": "ROSALES, JESUS",
                "Invoice Date": "2019-06-07",
                "Schedule Date": "0000-00-00",
                "Total": "-75.00"
            },
            {
                "Invoice": "273729",
                "Invoice Type": "Marketplace In State",
                "Sales Person": "Newegg",
                "Customer Name": "GAIL, CRYSTAL",
                "Invoice Date": "2019-06-08",
                "Schedule Date": "0000-00-00",
                "Total": "3,984.67"
            },
            {
                "Invoice": "273999",
                "Invoice Type": "Marketplace In State",
                "Sales Person": "Amazon",
                "Customer Name": "HYRE, TRAVIS",
                "Invoice Date": "2019-06-10",
                "Schedule Date": "0000-00-00",
                "Total": "181.60"
            },
            {
                "Invoice": "274126",
                "Invoice Type": "Marketplace In State",
                "Sales Person": "Walmart",
                "Customer Name": "MILLER, JUDY",
                "Invoice Date": "2019-06-10",
                "Schedule Date": "0000-00-00",
                "Total": "1,132.88"
            },
            {
                "Invoice": "274196",
                "Invoice Type": "Marketplace In State",
                "Sales Person": "Amazon",
                "Customer Name": "HESPENHEIDE, JOSEF J",
                "Invoice Date": "2019-06-11",
                "Schedule Date": "0000-00-00",
                "Total": "132.82"
            },
            {
                "Invoice": "191599",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Rita Jean",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2017-12-04",
                "Schedule Date": "0000-00-00",
                "Total": "3,918.06"
            },
            {
                "Invoice": "193579",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "House",
                "Customer Name": "LEE, YONGWOOK",
                "Invoice Date": "2017-12-19",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "194389",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "House",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2017-12-26",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "194685",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "House",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2017-12-29",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "196913",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-01-24",
                "Schedule Date": "0000-00-00",
                "Total": "1,696.99"
            },
            {
                "Invoice": "197757",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-01-31",
                "Schedule Date": "0000-00-00",
                "Total": "1,596.99"
            },
            {
                "Invoice": "197761",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-01-31",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "198578",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "SAMSUNG ELECTRONICS, CLAIMS",
                "Invoice Date": "2018-02-08",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "200153",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-02-22",
                "Schedule Date": "0000-00-00",
                "Total": "399.00"
            },
            {
                "Invoice": "202074",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-03-09",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "202532",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "SAMSUNG ELECTRONICS, CLAIMS",
                "Invoice Date": "2018-03-13",
                "Schedule Date": "0000-00-00",
                "Total": "1,017.68"
            },
            {
                "Invoice": "204061",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-21",
                "Schedule Date": "0000-00-00",
                "Total": "2,139.99"
            },
            {
                "Invoice": "204062",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-21",
                "Schedule Date": "0000-00-00",
                "Total": "2,139.99"
            },
            {
                "Invoice": "204605",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-03-23",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "205425",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Schedule Date": "0000-00-00",
                "Total": "788.00"
            },
            {
                "Invoice": "205434",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Schedule Date": "0000-00-00",
                "Total": "1,299.80"
            },
            {
                "Invoice": "205436",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Schedule Date": "0000-00-00",
                "Total": "96.99"
            },
            {
                "Invoice": "205438",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Schedule Date": "0000-00-00",
                "Total": "1,297.99"
            },
            {
                "Invoice": "205444",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Schedule Date": "0000-00-00",
                "Total": "1,084.88"
            },
            {
                "Invoice": "205446",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Schedule Date": "0000-00-00",
                "Total": "496.99"
            },
            {
                "Invoice": "205450",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Schedule Date": "0000-00-00",
                "Total": "2,947.99"
            },
            {
                "Invoice": "206025",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-03-29",
                "Schedule Date": "0000-00-00",
                "Total": "1,091.92"
            },
            {
                "Invoice": "206328",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-03-30",
                "Schedule Date": "0000-00-00",
                "Total": "9,999.99"
            },
            {
                "Invoice": "207822",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-04-05",
                "Schedule Date": "0000-00-00",
                "Total": "2,468.00"
            },
            {
                "Invoice": "209845",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-04-16",
                "Schedule Date": "0000-00-00",
                "Total": "2,596.99"
            },
            {
                "Invoice": "210846",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-04-23",
                "Schedule Date": "0000-00-00",
                "Total": "1,196.99"
            },
            {
                "Invoice": "211257",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-04-26",
                "Schedule Date": "0000-00-00",
                "Total": "1,396.99"
            },
            {
                "Invoice": "211263",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-04-26",
                "Schedule Date": "0000-00-00",
                "Total": "1,790.00"
            },
            {
                "Invoice": "211770",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-04-30",
                "Schedule Date": "0000-00-00",
                "Total": "1,196.99"
            },
            {
                "Invoice": "212443",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-05-04",
                "Schedule Date": "0000-00-00",
                "Total": "2,596.99"
            },
            {
                "Invoice": "220780",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Rita Jean",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-07-02",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "224046",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "BrianH",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-07-24",
                "Schedule Date": "0000-00-00",
                "Total": "1,996.99"
            },
            {
                "Invoice": "225355",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Rita Jean",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-08-03",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "227844",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Natalie",
                "Customer Name": "GARNETT, TANAJI",
                "Invoice Date": "2018-08-21",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "231751",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-20",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "231766",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-20",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "232129",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-24",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "232234",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-25",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "232236",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-25",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "232237",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-25",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "232238",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-25",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "232372",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CARRIER CLAIMS, FEDEX",
                "Invoice Date": "2018-09-26",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "232980",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-10-02",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "233013",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Tyana",
                "Customer Name": "WILLIAMS, TEST",
                "Invoice Date": "2018-10-02",
                "Schedule Date": "0000-00-00",
                "Total": "1,500.43"
            },
            {
                "Invoice": "233665",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Wade",
                "Customer Name": "CARRIER CLAIMS, FEDEX",
                "Invoice Date": "2018-10-09",
                "Schedule Date": "0000-00-00",
                "Total": "0.00"
            },
            {
                "Invoice": "234275",
                "Invoice Type": "Marketplace Sale",
                "Sales Person": "Peter",
                "Customer Name": "SHERER, TINA",
                "Invoice Date": "2018-10-16",
                "Schedule Date": "0000-00-00",
                "Total": "15.66"
            }
        ]';
    }

    protected function _getOpenOrdersByPartnerMockJson()
    {
        return '
        [
            {
                "Invoice": "273975",
                "Invoice Type": "Phone Sale Out Of State",
                "Order Status": "Do Not Ship",
                "Partner": "",
                "Customer Name": "DAVIS, CHRISS",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "",
                "Total": "810.00"
            },
            {
                "Invoice": "191599",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2017-12-04",
                "Partner Order Number": "",
                "Total": "3,918.06"
            },
            {
                "Invoice": "193579",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LEE, YONGWOOK",
                "Invoice Date": "2017-12-19",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "194389",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2017-12-26",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "194685",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2017-12-29",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "196913",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-01-24",
                "Partner Order Number": "",
                "Total": "1,696.99"
            },
            {
                "Invoice": "197757",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-01-31",
                "Partner Order Number": "",
                "Total": "1,596.99"
            },
            {
                "Invoice": "197761",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-01-31",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "198578",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "SAMSUNG ELECTRONICS, CLAIMS",
                "Invoice Date": "2018-02-08",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "200153",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-02-22",
                "Partner Order Number": "",
                "Total": "399.00"
            },
            {
                "Invoice": "202074",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-03-09",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "202532",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "SAMSUNG ELECTRONICS, CLAIMS",
                "Invoice Date": "2018-03-13",
                "Partner Order Number": "",
                "Total": "1,017.68"
            },
            {
                "Invoice": "204061",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-21",
                "Partner Order Number": "",
                "Total": "2,139.99"
            },
            {
                "Invoice": "204062",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-21",
                "Partner Order Number": "",
                "Total": "2,139.99"
            },
            {
                "Invoice": "204605",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-03-23",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "205425",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Partner Order Number": "",
                "Total": "788.00"
            },
            {
                "Invoice": "205434",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Partner Order Number": "",
                "Total": "1,299.80"
            },
            {
                "Invoice": "205436",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Partner Order Number": "",
                "Total": "96.99"
            },
            {
                "Invoice": "205438",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Partner Order Number": "",
                "Total": "1,297.99"
            },
            {
                "Invoice": "205444",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Partner Order Number": "",
                "Total": "1,084.88"
            },
            {
                "Invoice": "205446",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Partner Order Number": "",
                "Total": "496.99"
            },
            {
                "Invoice": "205450",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-03-27",
                "Partner Order Number": "",
                "Total": "2,947.99"
            },
            {
                "Invoice": "206025",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-03-29",
                "Partner Order Number": "",
                "Total": "1,091.92"
            },
            {
                "Invoice": "206328",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-03-30",
                "Partner Order Number": "",
                "Total": "9,999.99"
            },
            {
                "Invoice": "207822",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-04-05",
                "Partner Order Number": "",
                "Total": "2,468.00"
            },
            {
                "Invoice": "209845",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-04-16",
                "Partner Order Number": "",
                "Total": "2,596.99"
            },
            {
                "Invoice": "210846",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-04-23",
                "Partner Order Number": "",
                "Total": "1,196.99"
            },
            {
                "Invoice": "211257",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-04-26",
                "Partner Order Number": "",
                "Total": "1,396.99"
            },
            {
                "Invoice": "211263",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-04-26",
                "Partner Order Number": "",
                "Total": "1,790.00"
            },
            {
                "Invoice": "211770",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-04-30",
                "Partner Order Number": "",
                "Total": "1,196.99"
            },
            {
                "Invoice": "212443",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-05-04",
                "Partner Order Number": "",
                "Total": "2,596.99"
            },
            {
                "Invoice": "220780",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-07-02",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "224046",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-07-24",
                "Partner Order Number": "",
                "Total": "1,996.99"
            },
            {
                "Invoice": "225355",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-08-03",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "231751",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-20",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "231766",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-20",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "232129",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-24",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "232234",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-25",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "232236",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-25",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "232237",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-25",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "232238",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-09-25",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "232372",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CARRIER CLAIMS, FEDEX",
                "Invoice Date": "2018-09-26",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "232980",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-10-02",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "233665",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CARRIER CLAIMS, FEDEX",
                "Invoice Date": "2018-10-09",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "234275",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "SHERER, TINA",
                "Invoice Date": "2018-10-16",
                "Partner Order Number": "",
                "Total": "15.66"
            },
            {
                "Invoice": "236737",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2018-11-09",
                "Partner Order Number": "",
                "Total": "4,871.84"
            },
            {
                "Invoice": "237208",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "HENDLEY, ROBERT",
                "Invoice Date": "2018-11-14",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "238119",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "INGRAM MICRO, GEOFF HUNN",
                "Invoice Date": "2018-11-20",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "245808",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "LG ELECTRONICS , RA / CLAIM",
                "Invoice Date": "2018-12-05",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "246901",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "BUTIKIS, BARBARA",
                "Invoice Date": "2018-12-10",
                "Partner Order Number": "",
                "Total": "-904.59"
            },
            {
                "Invoice": "247279",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "MEEKS, SCOTT",
                "Invoice Date": "2018-12-11",
                "Partner Order Number": "",
                "Total": "135.12"
            },
            {
                "Invoice": "247292",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CENA-DOTEN, LAURA",
                "Invoice Date": "2018-12-11",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "250338",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2019-01-02",
                "Partner Order Number": "",
                "Total": "51.99"
            },
            {
                "Invoice": "251035",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "INGRAM MICRO, GEOFF HUNN",
                "Invoice Date": "2019-01-10",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "251374",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "SCOBEY, RICHARD",
                "Invoice Date": "2019-01-14",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "253176",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "ONTRAC,",
                "Invoice Date": "2019-01-29",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "259408",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "JOHNSON, KENNETH",
                "Invoice Date": "2019-03-15",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "261120",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2019-03-27",
                "Partner Order Number": "",
                "Total": "35.39"
            },
            {
                "Invoice": "261123",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2019-03-27",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "261176",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2019-03-27",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "261297",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2019-03-28",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "261452",
                "Invoice Type": "Phone Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "HENDLEY, STEVE",
                "Invoice Date": "2019-03-29",
                "Partner Order Number": "",
                "Total": "3,457.22"
            },
            {
                "Invoice": "261457",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2019-03-29",
                "Partner Order Number": "",
                "Total": "503.99"
            },
            {
                "Invoice": "261459",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2019-03-29",
                "Partner Order Number": "",
                "Total": "783.00"
            },
            {
                "Invoice": "261491",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CLAIMS, AMAZON",
                "Invoice Date": "2019-03-29",
                "Partner Order Number": "",
                "Total": "35.39"
            },
            {
                "Invoice": "262177",
                "Invoice Type": "Phone Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "HENRY, AUBREY",
                "Invoice Date": "2019-04-02",
                "Partner Order Number": "",
                "Total": "3,900.00"
            },
            {
                "Invoice": "263563",
                "Invoice Type": "Marketplace In State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "ESCAMILLO, TONY",
                "Invoice Date": "2019-04-12",
                "Partner Order Number": "",
                "Total": "81.08"
            },
            {
                "Invoice": "264350",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "COLEY, WILMA",
                "Invoice Date": "2019-04-16",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "267437",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "BRIAN, OQUINN",
                "Invoice Date": "2019-04-30",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "267546",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CARRIER CLAIMS, FEDEX",
                "Invoice Date": "2019-05-01",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "267815",
                "Invoice Type": "Phone Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "SWAIN, ALAN",
                "Invoice Date": "2019-05-02",
                "Partner Order Number": "",
                "Total": "11,299.95"
            },
            {
                "Invoice": "269666",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "INVOICE, TEST",
                "Invoice Date": "2019-05-14",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "269674",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "INVOICE, TEST",
                "Invoice Date": "2019-05-14",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "269844",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "INVOICE, TEST",
                "Invoice Date": "2019-05-15",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "269846",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "INVOICE, TEST",
                "Invoice Date": "2019-05-15",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "269847",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "INVOICE, TEST",
                "Invoice Date": "2019-05-15",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "269940",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "SCHMIDT, AARON",
                "Invoice Date": "2019-05-15",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "270056",
                "Invoice Type": "B2B Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-05-16",
                "Partner Order Number": "",
                "Total": "30,549.75"
            },
            {
                "Invoice": "270602",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "ALLEN, SCOTT",
                "Invoice Date": "2019-05-20",
                "Partner Order Number": "",
                "Total": "2,000.00"
            },
            {
                "Invoice": "270930",
                "Invoice Type": "Walts.com Sale In State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "WEINBURGER, TONY",
                "Invoice Date": "2019-05-21",
                "Partner Order Number": "",
                "Total": "4,053.75"
            },
            {
                "Invoice": "271984",
                "Invoice Type": "B2B Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-05-28",
                "Partner Order Number": "",
                "Total": "25,983.21"
            },
            {
                "Invoice": "271985",
                "Invoice Type": "B2B Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-05-28",
                "Partner Order Number": "",
                "Total": "17,481.55"
            },
            {
                "Invoice": "272007",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "WAKEFIELD, BRENDA",
                "Invoice Date": "2019-05-28",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "272286",
                "Invoice Type": "B2B Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-05-29",
                "Partner Order Number": "",
                "Total": "101,500.00"
            },
            {
                "Invoice": "272360",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "ULM, ERICH",
                "Invoice Date": "2019-05-29",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "272583",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "TV, WALTS",
                "Invoice Date": "2019-05-31",
                "Partner Order Number": "",
                "Total": "5,621.16"
            },
            {
                "Invoice": "273094",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "PRESTON, JEFF",
                "Invoice Date": "2019-06-03",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "273693",
                "Invoice Type": "B2B Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": ",",
                "Invoice Date": "2019-06-07",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "273739",
                "Invoice Type": "Phone Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "JUREKA, BRAD",
                "Invoice Date": "2019-06-08",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "273912",
                "Invoice Type": "Phone Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "FULTON, ROBERT",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "",
                "Total": "2,067.99"
            },
            {
                "Invoice": "273973",
                "Invoice Type": "B2B Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": ",",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "",
                "Total": "3,242.93"
            },
            {
                "Invoice": "274001",
                "Invoice Type": "B2B Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "",
                "Total": "7,911.81"
            },
            {
                "Invoice": "274002",
                "Invoice Type": "B2B Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "",
                "Total": "14,696.30"
            },
            {
                "Invoice": "274153",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CARRIER CLAIMS, FEDEX",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "",
                "Total": "2,205.00"
            },
            {
                "Invoice": "274167",
                "Invoice Type": "B2B Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "",
                "Total": "6,208.99"
            },
            {
                "Invoice": "274169",
                "Invoice Type": "Phone Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "SILLIK, DAVID",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "274181",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "BLUNIER, NATHAN",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "274198",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "BLASCHIK, OWEN J",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "274201",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "URBANCIC, CYRIL",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "",
                "Total": "-2,585.38"
            },
            {
                "Invoice": "274207",
                "Invoice Type": "B2B Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "",
                "Customer Name": "CART TOWN, JAY KIM",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "274187",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "Payment Verification",
                "Partner": "",
                "Customer Name": "URBANCIC, CYRIL",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "",
                "Total": "2,585.38"
            },
            {
                "Invoice": "274172",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "QC COMPLETE",
                "Partner": "",
                "Customer Name": "GAYNOR, CLAIRE",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "",
                "Total": "751.59"
            },
            {
                "Invoice": "265139",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "MATTHEWS, ROBERT",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "114-2299186-0601016-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265149",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "BHASIN, NS",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "113-1666758-4525011-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265167",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "DURAN, BILL",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "111-0259707-4600222-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265190",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": ", ALIE",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "112-2980442-0013023-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265191",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "HODGES, PAUL",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "114-2067243-9596261-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265193",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "PIKE, DAVID",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "111-3361393-8963405-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265198",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "PENROD, ED",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "112-3481300-3987445-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265201",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "TRESP, BRIAN",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "114-9986528-4577847-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265202",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "KHATAMI, FAZOL",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "111-9666574-7245833-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265205",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "MARQUET, BRI",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "113-4145881-6174633-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265257",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "MARCANO, ANGEL",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "111-9398148-6702607-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265260",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "ROWLAND, RANDY R",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "111-3746698-4727408-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265264",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "GUNTER, WALTER",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "114-5313540-0485814-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265268",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "ANDREWS, MICHAEL",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "111-0138424-1997076-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "265305",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "GIGLIO, KELLY",
                "Invoice Date": "2019-04-20",
                "Partner Order Number": "111-3690903-5380201-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "266015",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "HAMILTON, CHRISTINE",
                "Invoice Date": "2019-04-24",
                "Partner Order Number": "113-2132850-6205065-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "266896",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "ROSALES, DANIEL",
                "Invoice Date": "2019-04-28",
                "Partner Order Number": "112-2429509-4202616-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "267526",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "YUN, JAMES",
                "Invoice Date": "2019-05-01",
                "Partner Order Number": "114-0639399-0361827-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "267708",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "COPE, LISA C",
                "Invoice Date": "2019-05-02",
                "Partner Order Number": "111-8678425-4857065-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "268918",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "ACCARDO, ANTHONY AND BRE",
                "Invoice Date": "2019-05-09",
                "Partner Order Number": "111-2546162-0165011-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "269053",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "TIDERMAN, WILLIAM",
                "Invoice Date": "2019-05-09",
                "Partner Order Number": "114-2872977-1119460-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "269207",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "HUGGETTPOPO-19-1763, LINDA",
                "Invoice Date": "2019-05-11",
                "Partner Order Number": "112-7482019-3070600-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "269407",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "BACKHUS, ROSEMARIE",
                "Invoice Date": "2019-05-13",
                "Partner Order Number": "112-2052512-3447417-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "269554",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "MUFFOLETTO, DAN",
                "Invoice Date": "2019-05-13",
                "Partner Order Number": "114-4806431-4510635-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "269810",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "SHIHADEH, CHRISTINE",
                "Invoice Date": "2019-05-14",
                "Partner Order Number": "113-5023923-2521039-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "270018",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "SHIDE, RICHARD",
                "Invoice Date": "2019-05-16",
                "Partner Order Number": "113-2022488-7757830-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "271710",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "LOOKMANJEE, NABEEL",
                "Invoice Date": "2019-05-27",
                "Partner Order Number": "114-4424193-3299417-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "272029",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "",
                "Partner": "Amazon",
                "Customer Name": "EATON, GWENDOLYN",
                "Invoice Date": "2019-05-28",
                "Partner Order Number": "114-0343784-6685822-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "273893",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "2nd Attempt",
                "Partner": "Amazon",
                "Customer Name": "GARRY, RUSSELL",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-2382691-5239419",
                "Total": "2,845.83"
            },
            {
                "Invoice": "227844",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Claim Processed",
                "Partner": "Amazon",
                "Customer Name": "GARNETT, TANAJI",
                "Invoice Date": "2018-08-21",
                "Partner Order Number": "112-1696290-0115434-R",
                "Total": "0.00"
            },
            {
                "Invoice": "273915",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Do Not Ship",
                "Partner": "Amazon",
                "Customer Name": "AIA, ROBERT A GROVE,",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "114-9496851-1329844",
                "Total": "2,642.99"
            },
            {
                "Invoice": "273222",
                "Invoice Type": "Marketplace In State",
                "Order Status": "Local Sale",
                "Partner": "Amazon",
                "Customer Name": "GREEN, LARRY M",
                "Invoice Date": "2019-06-04",
                "Partner Order Number": "113-7451943-5204201",
                "Total": "1,068.98"
            },
            {
                "Invoice": "273428",
                "Invoice Type": "Marketplace In State",
                "Order Status": "Local Sale",
                "Partner": "Amazon",
                "Customer Name": "FASSETT, HOWARD",
                "Invoice Date": "2019-06-06",
                "Partner Order Number": "113-1825384-1276241",
                "Total": "3,076.34"
            },
            {
                "Invoice": "233013",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon",
                "Customer Name": "WILLIAMS, TEST",
                "Invoice Date": "2018-10-02",
                "Partner Order Number": "1234",
                "Total": "1,500.43"
            },
            {
                "Invoice": "261033",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon",
                "Customer Name": "SAFE T, AMAZON",
                "Invoice Date": "2019-03-26",
                "Partner Order Number": "114-3441152-7645063-Auto refund",
                "Total": "-197.99"
            },
            {
                "Invoice": "273498",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Paid Out Of Stock",
                "Partner": "Amazon",
                "Customer Name": "ENGINEERING DEPT, 14TH FLOOR, JAMES LANEY",
                "Invoice Date": "2019-06-06",
                "Partner Order Number": "113-4175319-0470651",
                "Total": "2,697.99"
            },
            {
                "Invoice": "274208",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "PRS Walts Parcel",
                "Partner": "Amazon",
                "Customer Name": "SMITH, LESTER",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "113-3977342-3282604",
                "Total": "40.55"
            },
            {
                "Invoice": "274211",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "PRS Walts Parcel",
                "Partner": "Amazon",
                "Customer Name": "ALMAN, LAUREN",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "112-9065803-5481811",
                "Total": "687.48"
            },
            {
                "Invoice": "273959",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "LEON, ALBERTO",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "114-3761989-4808247",
                "Total": "630.39"
            },
            {
                "Invoice": "273960",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "TINKELMAN, BRAD J",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-6124906-1833801",
                "Total": "696.99"
            },
            {
                "Invoice": "273968",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "ROBBINS, THOMAS",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "112-7581933-0330608",
                "Total": "318.99"
            },
            {
                "Invoice": "273971",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "DOLA, KULJEET",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-1198666-0396207",
                "Total": "3,101.95"
            },
            {
                "Invoice": "273972",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "FREDRICKSON, KYLE",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "111-0909263-5444245",
                "Total": "210.39"
            },
            {
                "Invoice": "273983",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "MULLEN, JIM",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-7087181-5609810",
                "Total": "210.16"
            },
            {
                "Invoice": "273989",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "ENDLICH, TODD",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "114-0940285-4511428",
                "Total": "1,086.72"
            },
            {
                "Invoice": "273991",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "BATTY, MICHAEL",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-6133028-8832240",
                "Total": "1,523.81"
            },
            {
                "Invoice": "273993",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "SINJORGO, EDWARD",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-6753453-0989068",
                "Total": "211.46"
            },
            {
                "Invoice": "274006",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "LINDHOLM, MAGNUS",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "112-5845072-1568204",
                "Total": "616.27"
            },
            {
                "Invoice": "274081",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "STROIKPO119006, LANCE",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "111-5786369-8521024",
                "Total": "85.89"
            },
            {
                "Invoice": "274083",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "NIEMEYER, NICHOLAS",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "114-7476095-5221818",
                "Total": "543.89"
            },
            {
                "Invoice": "274100",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "HIPPELI, DACIAN",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-6194831-9106642",
                "Total": "69.06"
            },
            {
                "Invoice": "274103",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "PATTERSON, DOMINICK",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "112-9230360-8216254",
                "Total": "41.09"
            },
            {
                "Invoice": "274105",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": ", MICHEL",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "111-0543561-1598657",
                "Total": "340.42"
            },
            {
                "Invoice": "274115",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "PORRAS, PHILLIP",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "112-3434314-0514617",
                "Total": "3,101.95"
            },
            {
                "Invoice": "274122",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "#5298, MARK ROSS GEARHART MD C/O",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-2665831-6502636",
                "Total": "498.98"
            },
            {
                "Invoice": "274125",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "MCGRATH, BRIAN",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-4607004-0245825",
                "Total": "3,078.33"
            },
            {
                "Invoice": "274127",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "WEINMANN, DANIEL",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-5031237-2380223",
                "Total": "3,049.31"
            },
            {
                "Invoice": "274133",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "LITTLE, PAUL",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "113-4071643-0629055",
                "Total": "543.91"
            },
            {
                "Invoice": "274135",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "KOSTBAR, ETHAN G",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "112-0129270-9044239",
                "Total": "618.99"
            },
            {
                "Invoice": "274140",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "HAUN, PAUL",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "114-7611835-8134651",
                "Total": "2,845.83"
            },
            {
                "Invoice": "274141",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "JUBAN, JOSEPH B",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "112-8537433-8153014",
                "Total": "41.46"
            },
            {
                "Invoice": "274143",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "GUNZELMANN, CAROL",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "114-1534780-6645823",
                "Total": "106.09"
            },
            {
                "Invoice": "274144",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "SHANLEY, MIKE",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "111-8975249-2552215",
                "Total": "37.97"
            },
            {
                "Invoice": "274145",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "HEGEL, ETHAN",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "114-1922000-8997002",
                "Total": "317.71"
            },
            {
                "Invoice": "274149",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "FEENEY, VIRGINIA",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "114-1471911-7806627",
                "Total": "2,801.03"
            },
            {
                "Invoice": "274154",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "COLOMBEL, ERIC",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "114-1044200-2592243",
                "Total": "1,397.99"
            },
            {
                "Invoice": "274168",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "ROOM, RANDALL FRETTPOBOSQUE CON",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "113-2819539-3601850",
                "Total": "698.78"
            },
            {
                "Invoice": "274178",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "LONG, RONALD",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "113-8881846-7828214",
                "Total": "15.67"
            },
            {
                "Invoice": "274196",
                "Invoice Type": "Marketplace In State",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "HESPENHEIDE, JOSEF J",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "112-3823860-2515461",
                "Total": "132.82"
            },
            {
                "Invoice": "274203",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "Amazon",
                "Customer Name": "SOLOMONIK, VLADIMIR",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "112-9599794-3179408",
                "Total": "41.39"
            },
            {
                "Invoice": "273390",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Tracking Pending",
                "Partner": "Amazon",
                "Customer Name": "VENGOECHEA, RICARDO A",
                "Invoice Date": "2019-06-06",
                "Partner Order Number": "111-5426518-0693850",
                "Total": "2,881.45"
            },
            {
                "Invoice": "273825",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Tracking Pending",
                "Partner": "Amazon",
                "Customer Name": "GENGLER, NATHAN J",
                "Invoice Date": "2019-06-09",
                "Partner Order Number": "114-9738548-8467454",
                "Total": "32.69"
            },
            {
                "Invoice": "273965",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Tracking Pending",
                "Partner": "Amazon",
                "Customer Name": "CARTER, LYNN",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "114-2784195-7433816",
                "Total": "179.41"
            },
            {
                "Invoice": "273976",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Tracking Pending",
                "Partner": "Amazon",
                "Customer Name": "ESTRELLA, CRISTOBAL",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-5621539-4122652",
                "Total": "183.11"
            },
            {
                "Invoice": "273999",
                "Invoice Type": "Marketplace In State",
                "Order Status": "Tracking Pending",
                "Partner": "Amazon",
                "Customer Name": "HYRE, TRAVIS",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "114-6634001-5030605",
                "Total": "181.60"
            },
            {
                "Invoice": "274101",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Tracking Pending",
                "Partner": "Amazon",
                "Customer Name": "WELLS, MICHELE SHEPHERD",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-0035159-1504249",
                "Total": "167.99"
            },
            {
                "Invoice": "274202",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Tracking Pending",
                "Partner": "Amazon",
                "Customer Name": "URBAN, SEAN",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "113-1500605-8353816",
                "Total": "1,110.87"
            },
            {
                "Invoice": "236670",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "MCKINNON, MATTHEW",
                "Invoice Date": "2018-11-08",
                "Partner Order Number": "111-0165665-9084212-r",
                "Total": "0.00"
            },
            {
                "Invoice": "246886",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.com",
                "Customer Name": "OJEDA, LEON",
                "Invoice Date": "2018-12-10",
                "Partner Order Number": "114-6845410-1121017-R",
                "Total": "-1,404.04"
            },
            {
                "Invoice": "262462",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "ROCHA, JOANNA",
                "Invoice Date": "2019-04-05",
                "Partner Order Number": "114-8003225-8759413-return",
                "Total": "0.00"
            },
            {
                "Invoice": "263998",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.com",
                "Customer Name": "LALL, AJ",
                "Invoice Date": "2019-04-15",
                "Partner Order Number": "113-3098112-6927404-Return",
                "Total": "-1,015.77"
            },
            {
                "Invoice": "264064",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "FOX, MISTY",
                "Invoice Date": "2019-04-15",
                "Partner Order Number": "114-1970620-5534667-return",
                "Total": "-1,998.00"
            },
            {
                "Invoice": "264708",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "BUONOMO, CHRISTINA",
                "Invoice Date": "2019-04-18",
                "Partner Order Number": "113-0925288-2556239-return",
                "Total": "-152.16"
            },
            {
                "Invoice": "265868",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.COM",
                "Customer Name": "HALL, SAM",
                "Invoice Date": "2019-04-23",
                "Partner Order Number": "114-3394008-7386640-Ret",
                "Total": "0.00"
            },
            {
                "Invoice": "268951",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.com",
                "Customer Name": ", GEORGE",
                "Invoice Date": "2019-05-09",
                "Partner Order Number": "112-7974952-7764201-Return",
                "Total": "-1,249.63"
            },
            {
                "Invoice": "268993",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "NAPOLITANO, CHRISTIAN",
                "Invoice Date": "2019-05-09",
                "Partner Order Number": "112-7078406-3039441-return",
                "Total": "-369.74"
            },
            {
                "Invoice": "269084",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "NAPOLITANO, CHRISTIAN",
                "Invoice Date": "2019-05-10",
                "Partner Order Number": "112-7078406-3039441-replacment",
                "Total": "0.00"
            },
            {
                "Invoice": "269586",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "WALTON, MICHAEL",
                "Invoice Date": "2019-05-13",
                "Partner Order Number": "112-4623061-9206655-return",
                "Total": "-681.37"
            },
            {
                "Invoice": "269684",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.com",
                "Customer Name": "INVOICE, TEST",
                "Invoice Date": "2019-05-14",
                "Partner Order Number": "123456789",
                "Total": "0.00"
            },
            {
                "Invoice": "269916",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "ASSOCIATES, NATIONAL VETERINARY",
                "Invoice Date": "2019-05-15",
                "Partner Order Number": "112-1519612-0325061-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "270204",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "LAURSEN, BRAD",
                "Invoice Date": "2019-05-17",
                "Partner Order Number": "3794958124754-return",
                "Total": "-2,077.73"
            },
            {
                "Invoice": "270262",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "MUSTEA, DACIAN",
                "Invoice Date": "2019-05-17",
                "Partner Order Number": "111-4462512-9361808-RETURN",
                "Total": "0.00"
            },
            {
                "Invoice": "270280",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.com",
                "Customer Name": "SLEINERS, MIKUS",
                "Invoice Date": "2019-05-17",
                "Partner Order Number": "111-3245009-7971438-Return",
                "Total": "-521.13"
            },
            {
                "Invoice": "270778",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.com",
                "Customer Name": "DORETY, IAN",
                "Invoice Date": "2019-05-21",
                "Partner Order Number": "112-0951037-5477003-Return",
                "Total": "-635.78"
            },
            {
                "Invoice": "271041",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "FULLINWIDER, JAMIE",
                "Invoice Date": "2019-05-22",
                "Partner Order Number": "114-4992647-9023420-c",
                "Total": "-40.00"
            },
            {
                "Invoice": "271104",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "COVARELLI, GIANNA",
                "Invoice Date": "2019-05-22",
                "Partner Order Number": "111-0170652-0643418-return",
                "Total": "-1,078.12"
            },
            {
                "Invoice": "271573",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "SHEFFIELD, JASON B",
                "Invoice Date": "2019-05-25",
                "Partner Order Number": "111-7436107-0345069-return",
                "Total": "0.00"
            },
            {
                "Invoice": "271969",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "SMITHHART, DAVID",
                "Invoice Date": "2019-05-28",
                "Partner Order Number": "112-5708267-3033051-return",
                "Total": "0.00"
            },
            {
                "Invoice": "272458",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.com",
                "Customer Name": "MOORE, JACINDA",
                "Invoice Date": "2019-05-30",
                "Partner Order Number": "113-2207287-5252239-Return",
                "Total": "-1,089.99"
            },
            {
                "Invoice": "272561",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "JENNINGS, ANTHONY",
                "Invoice Date": "2019-05-31",
                "Partner Order Number": "113-5351182-6007435-return",
                "Total": "0.00"
            },
            {
                "Invoice": "272696",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "ZHUKOV, FREIGHTREADYCOM",
                "Invoice Date": "2019-05-31",
                "Partner Order Number": "112-2965151-9500218-return",
                "Total": "-2,886.31"
            },
            {
                "Invoice": "272818",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "SMITHHART, DAVID",
                "Invoice Date": "2019-06-02",
                "Partner Order Number": "112-5708267-3033051-replace",
                "Total": "0.00"
            },
            {
                "Invoice": "272827",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "HAGEN, GAIL",
                "Invoice Date": "2019-06-02",
                "Partner Order Number": "112-9216963-6025047-RETURN",
                "Total": "-148.39"
            },
            {
                "Invoice": "272951",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.com",
                "Customer Name": "TLUCZEK, JOHN",
                "Invoice Date": "2019-06-03",
                "Partner Order Number": "112-9276859-2819463-Return",
                "Total": "-2,809.52"
            },
            {
                "Invoice": "273252",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "SCHIAVO, DAVID",
                "Invoice Date": "2019-06-05",
                "Partner Order Number": "114-4473535-3656233-return",
                "Total": "-2,822.71"
            },
            {
                "Invoice": "273340",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "MOORE, DENISE",
                "Invoice Date": "2019-06-05",
                "Partner Order Number": "68893-return",
                "Total": "-649.99"
            },
            {
                "Invoice": "273548",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "GREEN, JABARI",
                "Invoice Date": "2019-06-07",
                "Partner Order Number": "114-5931905-7255406-return",
                "Total": "-2,288.87"
            },
            {
                "Invoice": "273570",
                "Invoice Type": "Marketplace In State",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "ROSALES, JESUS",
                "Invoice Date": "2019-06-07",
                "Partner Order Number": "112-1337219-5538632-c",
                "Total": "-75.00"
            },
            {
                "Invoice": "273695",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "PARISH, CASIE J",
                "Invoice Date": "2019-06-07",
                "Partner Order Number": "113-5135597-3493868-return",
                "Total": "-2,886.85"
            },
            {
                "Invoice": "273891",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.COM",
                "Customer Name": "JACK, ASHLEY",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "112-2994313-2268235-Return",
                "Total": "0.00"
            },
            {
                "Invoice": "273917",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "STEVENSON, J",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-0108656-0780226-Return",
                "Total": "-149.79"
            },
            {
                "Invoice": "273969",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "CARR, JEFF",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-6075955-9012223-Return",
                "Total": "-58.00"
            },
            {
                "Invoice": "273982",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "NIES, DUSTIN",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "112-2922286-9456205-Return",
                "Total": "-1.09"
            },
            {
                "Invoice": "273988",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "BELLACH, TY",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "113-6497700-5875404-Return",
                "Total": "-632.19"
            },
            {
                "Invoice": "273990",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "LORA, ROBERT",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "112-9083924-6293850-Return",
                "Total": "-341.99"
            },
            {
                "Invoice": "273994",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "amazon.com",
                "Customer Name": "AUSTIN, CHARLES",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "112-2290470-4880220-Return",
                "Total": "-727.99"
            },
            {
                "Invoice": "274166",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.com",
                "Customer Name": "MORGAN BROWN,",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "114-7660428-6990644-Return",
                "Total": "-1,069.37"
            },
            {
                "Invoice": "274197",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.COM",
                "Customer Name": "ZUG, DIANE H",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "113-1066866-1476206-r",
                "Total": "-196.56"
            },
            {
                "Invoice": "274199",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.COM",
                "Customer Name": "SALIMI, BIJAN",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "113-6330675-3890653-r",
                "Total": "-118.94"
            },
            {
                "Invoice": "274200",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.COM",
                "Customer Name": "SLEEBA, SHIBU",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "111-8146783-8197866-r",
                "Total": "0.00"
            },
            {
                "Invoice": "274204",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Amazon.COM",
                "Customer Name": "THIRD FEDERAL SAVINGS, AJ EDWARDS",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "114-9163420-5267411-r",
                "Total": "-801.48"
            },
            {
                "Invoice": "273894",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Tracking Pending",
                "Partner": "Amazon.COM",
                "Customer Name": "JACK, ASHLEY",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "112-2994313-2268235-Replacement",
                "Total": "0.00"
            },
            {
                "Invoice": "274210",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Payment Verification",
                "Partner": "ebay",
                "Customer Name": "LU, WEIJIE",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "283506363886-1999826652018",
                "Total": "104.00"
            },
            {
                "Invoice": "274121",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "ebay",
                "Customer Name": "JANECEK, JOE",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "183408941912-1976878007008",
                "Total": "216.34"
            },
            {
                "Invoice": "274175",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "ebay",
                "Customer Name": "BRITTON, JACKSON",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "282887356666-1999754306018",
                "Total": "727.44"
            },
            {
                "Invoice": "274000",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Tracking Pending",
                "Partner": "ebay",
                "Customer Name": "THORSON, TERRY",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "283506471230-1999467812018",
                "Total": "678.34"
            },
            {
                "Invoice": "266884",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "EBAY Parts",
                "Customer Name": "AYBAR, MAYRA",
                "Invoice Date": "2019-04-28",
                "Partner Order Number": "778-Return",
                "Total": "-106.20"
            },
            {
                "Invoice": "273683",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "ebay.,com",
                "Customer Name": "LICHTENBERGER, BRIAN",
                "Invoice Date": "2019-06-07",
                "Partner Order Number": "283379251675-1990490261018-return",
                "Total": "-26.99"
            },
            {
                "Invoice": "269803",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "EBAY.COM",
                "Customer Name": "CLAYBON, STANLEY",
                "Invoice Date": "2019-05-14",
                "Partner Order Number": "282812088790-1989053884018-Refund",
                "Total": "-134.30"
            },
            {
                "Invoice": "272572",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "ebay.com",
                "Customer Name": "PICHON, SHMUEL",
                "Invoice Date": "2019-05-31",
                "Partner Order Number": "183620985242-1970086093008-Return",
                "Total": "-1,548.59"
            },
            {
                "Invoice": "274180",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "ebay.com",
                "Customer Name": "GLOVER, JERRY",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "183836376450-1975038807008-Return",
                "Total": "-29.99"
            },
            {
                "Invoice": "274209",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "ebay.com",
                "Customer Name": "BOCHSLER, DUSTIN",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "183836376450-1975090260008-return",
                "Total": "-29.99"
            },
            {
                "Invoice": "270752",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "ebayparts",
                "Customer Name": "RUNKLES, IAN",
                "Invoice Date": "2019-05-21",
                "Partner Order Number": "796-refund",
                "Total": "-16.95"
            },
            {
                "Invoice": "261265",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "greentoe.com",
                "Customer Name": "AGLIONE, KEVIN",
                "Invoice Date": "2019-03-28",
                "Partner Order Number": "PO-000166082-return",
                "Total": "-2,404.00"
            },
            {
                "Invoice": "273729",
                "Invoice Type": "Marketplace In State",
                "Order Status": "QC COMPLETE",
                "Partner": "newegg",
                "Customer Name": "GAIL, CRYSTAL",
                "Invoice Date": "2019-06-08",
                "Partner Order Number": "442141674",
                "Total": "3,984.67"
            },
            {
                "Invoice": "274182",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "newegg",
                "Customer Name": "CHEN, NELSON",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "442357294",
                "Total": "336.22"
            },
            {
                "Invoice": "257452",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "NewEgg.com",
                "Customer Name": "BARNHART, DOUGLAS",
                "Invoice Date": "2019-03-01",
                "Partner Order Number": "279115799-Replace",
                "Total": "0.00"
            },
            {
                "Invoice": "258543",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "newegg.com",
                "Customer Name": "ADAPA, BHUJANGA K",
                "Invoice Date": "2019-03-09",
                "Partner Order Number": "444293113-return",
                "Total": "-528.94"
            },
            {
                "Invoice": "274174",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "newegg_business",
                "Customer Name": "TURLEY, REBECCA",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "1201951106",
                "Total": "359.08"
            },
            {
                "Invoice": "274176",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Tracking Pending",
                "Partner": "rakuten",
                "Customer Name": "ADAMO, JARED",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "0047243-190611-1730007686",
                "Total": "1,915.98"
            },
            {
                "Invoice": "270200",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "rakuten.com",
                "Customer Name": "ADDURI, TANVI",
                "Invoice Date": "2019-05-17",
                "Partner Order Number": "0047243-190513-1747147592-return",
                "Total": "-89.00"
            },
            {
                "Invoice": "274005",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Rakuten.com",
                "Customer Name": "VEGA, TAMARA",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "0047243-190526-0100337980-Return",
                "Total": "0.00"
            },
            {
                "Invoice": "256821",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Sears.com",
                "Customer Name": "AGUIEIRAS, SERINA M",
                "Invoice Date": "2019-02-25",
                "Partner Order Number": "1154777-PP",
                "Total": "-114.00"
            },
            {
                "Invoice": "263404",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "sears.com",
                "Customer Name": "WILLIAMS, GINA",
                "Invoice Date": "2019-04-11",
                "Partner Order Number": "2073075-return",
                "Total": "0.00"
            },
            {
                "Invoice": "270781",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Sears.com",
                "Customer Name": "STROUP, BOB",
                "Invoice Date": "2019-05-21",
                "Partner Order Number": "2080380-replacement-Return",
                "Total": "0.00"
            },
            {
                "Invoice": "254141",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Walmart.com",
                "Customer Name": "GAVILANES, ALESHIA",
                "Invoice Date": "2019-02-05",
                "Partner Order Number": "2790292262507-1-C",
                "Total": "0.00"
            },
            {
                "Invoice": "258611",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walmart.com",
                "Customer Name": "PITA, JESUS",
                "Invoice Date": "2019-03-10",
                "Partner Order Number": "4790600032049-return",
                "Total": "-148.95"
            },
            {
                "Invoice": "268221",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Walmart.com",
                "Customer Name": "GORDON, TERRY",
                "Invoice Date": "2019-05-05",
                "Partner Order Number": "1794875880065-Return",
                "Total": "-686.09"
            },
            {
                "Invoice": "270997",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walmart.com",
                "Customer Name": "PETRELLA, LAWRENCE",
                "Invoice Date": "2019-05-22",
                "Partner Order Number": "3795071062684-return",
                "Total": "0.00"
            },
            {
                "Invoice": "271199",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Walmart.com",
                "Customer Name": "ZHU, KEVIN",
                "Invoice Date": "2019-05-23",
                "Partner Order Number": "4791360717493-Return",
                "Total": "-696.99"
            },
            {
                "Invoice": "271439",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walmart.com",
                "Customer Name": "SILBERNAGEL, MICHAEL",
                "Invoice Date": "2019-05-24",
                "Partner Order Number": "1795132872752-concession",
                "Total": "30.00"
            },
            {
                "Invoice": "271508",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walmart.com",
                "Customer Name": "HAFEEZ, JAMAL",
                "Invoice Date": "2019-05-24",
                "Partner Order Number": "1795050545513-return",
                "Total": "-744.39"
            },
            {
                "Invoice": "272795",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walmart.com",
                "Customer Name": "MACAULEY, TAMARA",
                "Invoice Date": "2019-06-02",
                "Partner Order Number": "3795225090969-C",
                "Total": "-100.00"
            },
            {
                "Invoice": "272879",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walmart.com",
                "Customer Name": "JACKSON, CHERYL",
                "Invoice Date": "2019-06-03",
                "Partner Order Number": "4791473421370-return",
                "Total": "-597.94"
            },
            {
                "Invoice": "273229",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walmart.com",
                "Customer Name": "DELAY, CINDY",
                "Invoice Date": "2019-06-04",
                "Partner Order Number": "2791452924000-RETURN",
                "Total": "-2,519.99"
            },
            {
                "Invoice": "273476",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walmart.com",
                "Customer Name": "REYNOLDS, MALETA",
                "Invoice Date": "2019-06-06",
                "Partner Order Number": "2791483709921-c",
                "Total": "-100.00"
            },
            {
                "Invoice": "273488",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walmart.com",
                "Customer Name": "CRICKENBERGER, JON",
                "Invoice Date": "2019-06-06",
                "Partner Order Number": "1795194323920-return",
                "Total": "-2,096.90"
            },
            {
                "Invoice": "273586",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Walmart.com",
                "Customer Name": "MCKINNON, LISA",
                "Invoice Date": "2019-06-07",
                "Partner Order Number": "2791360763972-Return",
                "Total": "-46.90"
            },
            {
                "Invoice": "273588",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walmart.com",
                "Customer Name": "SIMPSON, TATE",
                "Invoice Date": "2019-06-07",
                "Partner Order Number": "4791473538811-return",
                "Total": "-818.42"
            },
            {
                "Invoice": "273765",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walmart.com",
                "Customer Name": "BHULLAR, NEETU",
                "Invoice Date": "2019-06-08",
                "Partner Order Number": "3795173589944-return",
                "Total": "-1,716.09"
            },
            {
                "Invoice": "274138",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Walmart.com",
                "Customer Name": "CLAIMS, WALMART",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "2790527586652-RI",
                "Total": "1,680.48"
            },
            {
                "Invoice": "273733",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "Paid Out Of Stock",
                "Partner": "walmart.com",
                "Customer Name": "JUREKA, BRAD",
                "Invoice Date": "2019-06-08",
                "Partner Order Number": "4791606987829",
                "Total": "4,304.41"
            },
            {
                "Invoice": "273964",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "GRAGGS, DR KIM",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "4791627556035",
                "Total": "2,910.36"
            },
            {
                "Invoice": "273992",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "DONADIO, ANNE",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "1795317554433",
                "Total": "916.98"
            },
            {
                "Invoice": "274123",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "SCHILLINGER, DENNIS",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "3795327640540",
                "Total": "589.57"
            },
            {
                "Invoice": "274126",
                "Invoice Type": "Marketplace In State",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "MILLER, JUDY",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "3795327644510",
                "Total": "1,132.88"
            },
            {
                "Invoice": "274137",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "GARCIA, MARIA",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "1795327690507",
                "Total": "2,845.83"
            },
            {
                "Invoice": "274139",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "DUANE, TOWNSEND",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "1795327700166",
                "Total": "996.88"
            },
            {
                "Invoice": "274142",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "PRICE, JUNEANNE",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "4791637728034",
                "Total": "58.00"
            },
            {
                "Invoice": "274148",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "WRIGHT, DARRELL",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "3795327722026",
                "Total": "534.68"
            },
            {
                "Invoice": "274151",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "KIJEWSKI, JULIAN",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "1795327737813",
                "Total": "727.44"
            },
            {
                "Invoice": "274156",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "HEREFORD, BARBARA",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "2791637777858",
                "Total": "786.65"
            },
            {
                "Invoice": "274157",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "MOSCOSO, CARLOS",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "1795327753637",
                "Total": "1,915.98"
            },
            {
                "Invoice": "274162",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "HARALSON, CHERISSE",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "4791637782417",
                "Total": "2,676.60"
            },
            {
                "Invoice": "274163",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "HARALSON, CHERISSE",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "4791637782416",
                "Total": "426.45"
            },
            {
                "Invoice": "274164",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "AUMAN, DEBORAH",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "1795327757572",
                "Total": "1,058.09"
            },
            {
                "Invoice": "274165",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walmart.com",
                "Customer Name": "GARGUS, PAULA",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "4791637787017",
                "Total": "727.44"
            },
            {
                "Invoice": "273508",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "WAITING PACK",
                "Partner": "walmart.com",
                "Customer Name": "MARKHAM, BARBARA",
                "Invoice Date": "2019-06-06",
                "Partner Order Number": "2791596566746",
                "Total": "255.30"
            },
            {
                "Invoice": "247933",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "Control 4",
                "Partner": "walts.com",
                "Customer Name": "ALCORN, DERECK",
                "Invoice Date": "2018-12-14",
                "Partner Order Number": "67949",
                "Total": "149.97"
            },
            {
                "Invoice": "271865",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "Do Not Ship",
                "Partner": "walts.com",
                "Customer Name": "MASCIOLA, ANNAMARIE",
                "Invoice Date": "2019-05-27",
                "Partner Order Number": "69271",
                "Total": "1,619.29"
            },
            {
                "Invoice": "273378",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "Do Not Ship",
                "Partner": "walts.com",
                "Customer Name": "SIMMONS, SHELLY",
                "Invoice Date": "2019-06-05",
                "Partner Order Number": "69611",
                "Total": "2,697.99"
            },
            {
                "Invoice": "267620",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "HAAS, ADAM",
                "Invoice Date": "2019-05-01",
                "Partner Order Number": "234544-replace",
                "Total": "0.00"
            },
            {
                "Invoice": "271240",
                "Invoice Type": "Marketplace In State",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "WARREN, GIL",
                "Invoice Date": "2019-05-23",
                "Partner Order Number": "270467-return",
                "Total": "0.00"
            },
            {
                "Invoice": "271851",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "KIM, KYENG TAE",
                "Invoice Date": "2019-05-27",
                "Partner Order Number": "69229",
                "Total": "1,466.99"
            },
            {
                "Invoice": "271979",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Walts.com",
                "Customer Name": "GILL, EDITH",
                "Invoice Date": "2019-05-28",
                "Partner Order Number": "68781-Return",
                "Total": "-4,031.19"
            },
            {
                "Invoice": "272834",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "HUNTER, JOCELYN",
                "Invoice Date": "2019-06-02",
                "Partner Order Number": "69579",
                "Total": "323.25"
            },
            {
                "Invoice": "273138",
                "Invoice Type": "Walts.com Sale In State",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "NOBODY, JOE",
                "Invoice Date": "2019-06-04",
                "Partner Order Number": "69594",
                "Total": "1,584.74"
            },
            {
                "Invoice": "273504",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "KALYANASUNDARAM, ARVIND",
                "Invoice Date": "2019-06-06",
                "Partner Order Number": "69430-return",
                "Total": "-996.99"
            },
            {
                "Invoice": "273505",
                "Invoice Type": "Phone Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "STUMPF, JARAMIE",
                "Invoice Date": "2019-06-06",
                "Partner Order Number": "259004-Return",
                "Total": "-69.98"
            },
            {
                "Invoice": "273544",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "LEE, KENNETH",
                "Invoice Date": "2019-06-07",
                "Partner Order Number": "69443-return",
                "Total": "-996.99"
            },
            {
                "Invoice": "273552",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "PATTERSON, MICHAEL",
                "Invoice Date": "2019-06-07",
                "Partner Order Number": "68819-return",
                "Total": "-3,484.72"
            },
            {
                "Invoice": "273692",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "OPIPARI, MARK",
                "Invoice Date": "2019-06-07",
                "Partner Order Number": "271809-return",
                "Total": "-996.99"
            },
            {
                "Invoice": "273740",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "JUREKA, BRAD",
                "Invoice Date": "2019-06-08",
                "Partner Order Number": "69646",
                "Total": "4,184.51"
            },
            {
                "Invoice": "273852",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "TALWAR, ASHEESH",
                "Invoice Date": "2019-06-09",
                "Partner Order Number": "69653",
                "Total": "2,800.00"
            },
            {
                "Invoice": "273882",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "MOSKALEVA, OLGA",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "69037-return",
                "Total": "-1,557.06"
            },
            {
                "Invoice": "273896",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "SHIN, KI",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "69187-Return",
                "Total": "-1,580.30"
            },
            {
                "Invoice": "273921",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "SKELTON, ORRY",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "69663",
                "Total": "2,991.80"
            },
            {
                "Invoice": "273935",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "BURPO, RYAN",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "69665",
                "Total": "559.99"
            },
            {
                "Invoice": "273957",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "Walts.com",
                "Customer Name": "BOLEN, ROBERT",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "69585-return",
                "Total": "-579.99"
            },
            {
                "Invoice": "274136",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "WINSTON, JOSH",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "68930-Return",
                "Total": "-1,482.94"
            },
            {
                "Invoice": "274192",
                "Invoice Type": "Chat Sale Out Of State",
                "Order Status": "NEW ORDER",
                "Partner": "walts.com",
                "Customer Name": "URBANCIC, CYRIL",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "69610-Return",
                "Total": "0.00"
            },
            {
                "Invoice": "273735",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "QC COMPLETE",
                "Partner": "walts.com",
                "Customer Name": "LUCAS, DORIS",
                "Invoice Date": "2019-06-08",
                "Partner Order Number": "69645",
                "Total": "417.99"
            },
            {
                "Invoice": "273997",
                "Invoice Type": "Walts.com Sale Out Of State",
                "Order Status": "QC COMPLETE",
                "Partner": "walts.com",
                "Customer Name": "BEKOE, PRINCE",
                "Invoice Date": "2019-06-10",
                "Partner Order Number": "69668",
                "Total": "2,497.99"
            },
            {
                "Invoice": "274173",
                "Invoice Type": "Marketplace Sale",
                "Order Status": "QC COMPLETE",
                "Partner": "walts.com",
                "Customer Name": "SATHER, GLEN",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "",
                "Total": "0.00"
            },
            {
                "Invoice": "274177",
                "Invoice Type": "Walts.com Sale In State",
                "Order Status": "QC COMPLETE",
                "Partner": "walts.com",
                "Customer Name": "QUINTANA, MICHAEL",
                "Invoice Date": "2019-06-11",
                "Partner Order Number": "69671",
                "Total": "1,835.53"
            }
        ]';
    }

    protected function _getOpenOrdersByOrderStatusMockJson()
    {
        return '
        [
			{
				"Invoice": "265139",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "MATTHEWS, ROBERT",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "114-2299186-0601016-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265149",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "BHASIN, NS",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "113-1666758-4525011-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265167",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "DURAN, BILL",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "111-0259707-4600222-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265190",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": ", ALIE",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "112-2980442-0013023-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265191",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "HODGES, PAUL",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "114-2067243-9596261-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265193",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "PIKE, DAVID",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "111-3361393-8963405-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265198",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "PENROD, ED",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "112-3481300-3987445-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265201",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "TRESP, BRIAN",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "114-9986528-4577847-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265202",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "KHATAMI, FAZOL",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "111-9666574-7245833-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265205",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "MARQUET, BRI",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "113-4145881-6174633-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265257",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "MARCANO, ANGEL",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "111-9398148-6702607-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265260",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "ROWLAND, RANDY R",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "111-3746698-4727408-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265264",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "GUNTER, WALTER",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "114-5313540-0485814-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265268",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "ANDREWS, MICHAEL",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "111-0138424-1997076-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "265305",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "GIGLIO, KELLY",
				"Invoice Date": "2019-04-20",
				"Partner Order Number": "111-3690903-5380201-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "266015",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "HAMILTON, CHRISTINE",
				"Invoice Date": "2019-04-24",
				"Partner Order Number": "113-2132850-6205065-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "266896",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "ROSALES, DANIEL",
				"Invoice Date": "2019-04-28",
				"Partner Order Number": "112-2429509-4202616-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "267526",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "YUN, JAMES",
				"Invoice Date": "2019-05-01",
				"Partner Order Number": "114-0639399-0361827-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "267708",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "COPE, LISA C",
				"Invoice Date": "2019-05-02",
				"Partner Order Number": "111-8678425-4857065-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "268918",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "ACCARDO, ANTHONY AND BRE",
				"Invoice Date": "2019-05-09",
				"Partner Order Number": "111-2546162-0165011-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "269053",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "TIDERMAN, WILLIAM",
				"Invoice Date": "2019-05-09",
				"Partner Order Number": "114-2872977-1119460-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "269207",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "HUGGETTPOPO-19-1763, LINDA",
				"Invoice Date": "2019-05-11",
				"Partner Order Number": "112-7482019-3070600-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "269407",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "BACKHUS, ROSEMARIE",
				"Invoice Date": "2019-05-13",
				"Partner Order Number": "112-2052512-3447417-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "269554",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "MUFFOLETTO, DAN",
				"Invoice Date": "2019-05-13",
				"Partner Order Number": "114-4806431-4510635-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "269810",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "SHIHADEH, CHRISTINE",
				"Invoice Date": "2019-05-14",
				"Partner Order Number": "113-5023923-2521039-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "270018",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "SHIDE, RICHARD",
				"Invoice Date": "2019-05-16",
				"Partner Order Number": "113-2022488-7757830-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "271710",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "LOOKMANJEE, NABEEL",
				"Invoice Date": "2019-05-27",
				"Partner Order Number": "114-4424193-3299417-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "272029",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "",
				"Partner": "Amazon",
				"Customer Name": "EATON, GWENDOLYN",
				"Invoice Date": "2019-05-28",
				"Partner Order Number": "114-0343784-6685822-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "274340",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "AWAITING PICKING",
				"Partner": "Amazon",
				"Customer Name": "PO6644272-02, RENE LAFONTAINE",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "114-5292628-1379446",
				"Total": "1,069.35"
			},
			{
				"Invoice": "227844",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Claim Processed",
				"Partner": "Amazon",
				"Customer Name": "GARNETT, TANAJI",
				"Invoice Date": "2018-08-21",
				"Partner Order Number": "112-1696290-0115434-R",
				"Total": "0.00"
			},
			{
				"Invoice": "247933",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "Control 4",
				"Partner": "walts.com",
				"Customer Name": "ALCORN, DERECK",
				"Invoice Date": "2018-12-14",
				"Partner Order Number": "67949",
				"Total": "149.97"
			},
			{
				"Invoice": "271865",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "Do Not Ship",
				"Partner": "walts.com",
				"Customer Name": "MASCIOLA, ANNAMARIE",
				"Invoice Date": "2019-05-27",
				"Partner Order Number": "69271",
				"Total": "1,619.29"
			},
			{
				"Invoice": "273378",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "Do Not Ship",
				"Partner": "walts.com",
				"Customer Name": "SIMMONS, SHELLY",
				"Invoice Date": "2019-06-05",
				"Partner Order Number": "69611",
				"Total": "2,697.99"
			},
			{
				"Invoice": "273915",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Do Not Ship",
				"Partner": "Amazon",
				"Customer Name": "AIA, ROBERT A GROVE,",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "114-9496851-1329844",
				"Total": "2,642.99"
			},
			{
				"Invoice": "273975",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "Do Not Ship",
				"Partner": "",
				"Customer Name": "DAVIS, CHRISS",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "",
				"Total": "810.00"
			},
			{
				"Invoice": "273222",
				"Invoice Type": "Marketplace In State",
				"Order Status": "Local Sale",
				"Partner": "Amazon",
				"Customer Name": "GREEN, LARRY M",
				"Invoice Date": "2019-06-04",
				"Partner Order Number": "113-7451943-5204201",
				"Total": "1,068.98"
			},
			{
				"Invoice": "273428",
				"Invoice Type": "Marketplace In State",
				"Order Status": "Local Sale",
				"Partner": "Amazon",
				"Customer Name": "FASSETT, HOWARD",
				"Invoice Date": "2019-06-06",
				"Partner Order Number": "113-1825384-1276241",
				"Total": "3,076.34"
			},
			{
				"Invoice": "191599",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2017-12-04",
				"Partner Order Number": "",
				"Total": "3,918.06"
			},
			{
				"Invoice": "193579",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LEE, YONGWOOK",
				"Invoice Date": "2017-12-19",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "194389",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2017-12-26",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "194685",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2017-12-29",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "196913",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-01-24",
				"Partner Order Number": "",
				"Total": "1,696.99"
			},
			{
				"Invoice": "197757",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-01-31",
				"Partner Order Number": "",
				"Total": "1,596.99"
			},
			{
				"Invoice": "197761",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-01-31",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "198578",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "SAMSUNG ELECTRONICS, CLAIMS",
				"Invoice Date": "2018-02-08",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "200153",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-02-22",
				"Partner Order Number": "",
				"Total": "399.00"
			},
			{
				"Invoice": "202074",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LG ELECTRONICS , RA / CLAIM",
				"Invoice Date": "2018-03-09",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "202532",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "SAMSUNG ELECTRONICS, CLAIMS",
				"Invoice Date": "2018-03-13",
				"Partner Order Number": "",
				"Total": "1,017.68"
			},
			{
				"Invoice": "204061",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-03-21",
				"Partner Order Number": "",
				"Total": "2,139.99"
			},
			{
				"Invoice": "204062",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-03-21",
				"Partner Order Number": "",
				"Total": "2,139.99"
			},
			{
				"Invoice": "204605",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LG ELECTRONICS , RA / CLAIM",
				"Invoice Date": "2018-03-23",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "205425",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-03-27",
				"Partner Order Number": "",
				"Total": "788.00"
			},
			{
				"Invoice": "205434",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-03-27",
				"Partner Order Number": "",
				"Total": "1,299.80"
			},
			{
				"Invoice": "205436",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-03-27",
				"Partner Order Number": "",
				"Total": "96.99"
			},
			{
				"Invoice": "205438",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-03-27",
				"Partner Order Number": "",
				"Total": "1,297.99"
			},
			{
				"Invoice": "205444",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-03-27",
				"Partner Order Number": "",
				"Total": "1,084.88"
			},
			{
				"Invoice": "205446",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-03-27",
				"Partner Order Number": "",
				"Total": "496.99"
			},
			{
				"Invoice": "205450",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-03-27",
				"Partner Order Number": "",
				"Total": "2,947.99"
			},
			{
				"Invoice": "206025",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LG ELECTRONICS , RA / CLAIM",
				"Invoice Date": "2018-03-29",
				"Partner Order Number": "",
				"Total": "1,091.92"
			},
			{
				"Invoice": "206328",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LG ELECTRONICS , RA / CLAIM",
				"Invoice Date": "2018-03-30",
				"Partner Order Number": "",
				"Total": "9,999.99"
			},
			{
				"Invoice": "207822",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LG ELECTRONICS , RA / CLAIM",
				"Invoice Date": "2018-04-05",
				"Partner Order Number": "",
				"Total": "2,468.00"
			},
			{
				"Invoice": "209845",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LG ELECTRONICS , RA / CLAIM",
				"Invoice Date": "2018-04-16",
				"Partner Order Number": "",
				"Total": "2,596.99"
			},
			{
				"Invoice": "210846",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LG ELECTRONICS , RA / CLAIM",
				"Invoice Date": "2018-04-23",
				"Partner Order Number": "",
				"Total": "1,196.99"
			},
			{
				"Invoice": "211257",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LG ELECTRONICS , RA / CLAIM",
				"Invoice Date": "2018-04-26",
				"Partner Order Number": "",
				"Total": "1,396.99"
			},
			{
				"Invoice": "211263",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LG ELECTRONICS , RA / CLAIM",
				"Invoice Date": "2018-04-26",
				"Partner Order Number": "",
				"Total": "1,790.00"
			},
			{
				"Invoice": "211770",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LG ELECTRONICS , RA / CLAIM",
				"Invoice Date": "2018-04-30",
				"Partner Order Number": "",
				"Total": "1,196.99"
			},
			{
				"Invoice": "212443",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LG ELECTRONICS , RA / CLAIM",
				"Invoice Date": "2018-05-04",
				"Partner Order Number": "",
				"Total": "2,596.99"
			},
			{
				"Invoice": "220780",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-07-02",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "224046",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LG ELECTRONICS , RA / CLAIM",
				"Invoice Date": "2018-07-24",
				"Partner Order Number": "",
				"Total": "1,996.99"
			},
			{
				"Invoice": "225355",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-08-03",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "231751",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-09-20",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "231766",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-09-20",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "232129",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-09-24",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "232234",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-09-25",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "232236",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-09-25",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "232237",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-09-25",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "232238",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-09-25",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "232372",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CARRIER CLAIMS, FEDEX",
				"Invoice Date": "2018-09-26",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "232980",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-10-02",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "233013",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon",
				"Customer Name": "WILLIAMS, TEST",
				"Invoice Date": "2018-10-02",
				"Partner Order Number": "1234",
				"Total": "1,500.43"
			},
			{
				"Invoice": "233665",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CARRIER CLAIMS, FEDEX",
				"Invoice Date": "2018-10-09",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "234275",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "SHERER, TINA",
				"Invoice Date": "2018-10-16",
				"Partner Order Number": "",
				"Total": "15.66"
			},
			{
				"Invoice": "236670",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "MCKINNON, MATTHEW",
				"Invoice Date": "2018-11-08",
				"Partner Order Number": "111-0165665-9084212-r",
				"Total": "0.00"
			},
			{
				"Invoice": "236737",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2018-11-09",
				"Partner Order Number": "",
				"Total": "4,871.84"
			},
			{
				"Invoice": "237208",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "HENDLEY, ROBERT",
				"Invoice Date": "2018-11-14",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "238119",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "INGRAM MICRO, GEOFF HUNN",
				"Invoice Date": "2018-11-20",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "245808",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "LG ELECTRONICS , RA / CLAIM",
				"Invoice Date": "2018-12-05",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "246886",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.com",
				"Customer Name": "OJEDA, LEON",
				"Invoice Date": "2018-12-10",
				"Partner Order Number": "114-6845410-1121017-R",
				"Total": "-1,404.04"
			},
			{
				"Invoice": "246901",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "BUTIKIS, BARBARA",
				"Invoice Date": "2018-12-10",
				"Partner Order Number": "",
				"Total": "-904.59"
			},
			{
				"Invoice": "247279",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "MEEKS, SCOTT",
				"Invoice Date": "2018-12-11",
				"Partner Order Number": "",
				"Total": "135.12"
			},
			{
				"Invoice": "247292",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CENA-DOTEN, LAURA",
				"Invoice Date": "2018-12-11",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "250338",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2019-01-02",
				"Partner Order Number": "",
				"Total": "51.99"
			},
			{
				"Invoice": "251035",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "INGRAM MICRO, GEOFF HUNN",
				"Invoice Date": "2019-01-10",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "251374",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "SCOBEY, RICHARD",
				"Invoice Date": "2019-01-14",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "253176",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "ONTRAC,",
				"Invoice Date": "2019-01-29",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "254141",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Walmart.com",
				"Customer Name": "GAVILANES, ALESHIA",
				"Invoice Date": "2019-02-05",
				"Partner Order Number": "2790292262507-1-C",
				"Total": "0.00"
			},
			{
				"Invoice": "256821",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Sears.com",
				"Customer Name": "AGUIEIRAS, SERINA M",
				"Invoice Date": "2019-02-25",
				"Partner Order Number": "1154777-PP",
				"Total": "-114.00"
			},
			{
				"Invoice": "257452",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "NewEgg.com",
				"Customer Name": "BARNHART, DOUGLAS",
				"Invoice Date": "2019-03-01",
				"Partner Order Number": "279115799-Replace",
				"Total": "0.00"
			},
			{
				"Invoice": "258543",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "newegg.com",
				"Customer Name": "ADAPA, BHUJANGA K",
				"Invoice Date": "2019-03-09",
				"Partner Order Number": "444293113-return",
				"Total": "-528.94"
			},
			{
				"Invoice": "258611",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walmart.com",
				"Customer Name": "PITA, JESUS",
				"Invoice Date": "2019-03-10",
				"Partner Order Number": "4790600032049-return",
				"Total": "-148.95"
			},
			{
				"Invoice": "259408",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "JOHNSON, KENNETH",
				"Invoice Date": "2019-03-15",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "261033",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon",
				"Customer Name": "SAFE T, AMAZON",
				"Invoice Date": "2019-03-26",
				"Partner Order Number": "114-3441152-7645063-Auto refund",
				"Total": "-197.99"
			},
			{
				"Invoice": "261120",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2019-03-27",
				"Partner Order Number": "",
				"Total": "35.39"
			},
			{
				"Invoice": "261123",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2019-03-27",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "261176",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2019-03-27",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "261265",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "greentoe.com",
				"Customer Name": "AGLIONE, KEVIN",
				"Invoice Date": "2019-03-28",
				"Partner Order Number": "PO-000166082-return",
				"Total": "-2,404.00"
			},
			{
				"Invoice": "261297",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2019-03-28",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "261452",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "HENDLEY, STEVE",
				"Invoice Date": "2019-03-29",
				"Partner Order Number": "",
				"Total": "3,457.22"
			},
			{
				"Invoice": "261457",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2019-03-29",
				"Partner Order Number": "",
				"Total": "503.99"
			},
			{
				"Invoice": "261459",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2019-03-29",
				"Partner Order Number": "",
				"Total": "783.00"
			},
			{
				"Invoice": "261491",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CLAIMS, AMAZON",
				"Invoice Date": "2019-03-29",
				"Partner Order Number": "",
				"Total": "35.39"
			},
			{
				"Invoice": "262177",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "HENRY, AUBREY",
				"Invoice Date": "2019-04-02",
				"Partner Order Number": "",
				"Total": "3,900.00"
			},
			{
				"Invoice": "262462",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "ROCHA, JOANNA",
				"Invoice Date": "2019-04-05",
				"Partner Order Number": "114-8003225-8759413-return",
				"Total": "0.00"
			},
			{
				"Invoice": "263404",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "sears.com",
				"Customer Name": "WILLIAMS, GINA",
				"Invoice Date": "2019-04-11",
				"Partner Order Number": "2073075-return",
				"Total": "0.00"
			},
			{
				"Invoice": "263563",
				"Invoice Type": "Marketplace In State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "ESCAMILLO, TONY",
				"Invoice Date": "2019-04-12",
				"Partner Order Number": "",
				"Total": "81.08"
			},
			{
				"Invoice": "263998",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.com",
				"Customer Name": "LALL, AJ",
				"Invoice Date": "2019-04-15",
				"Partner Order Number": "113-3098112-6927404-Return",
				"Total": "-1,015.77"
			},
			{
				"Invoice": "264064",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "FOX, MISTY",
				"Invoice Date": "2019-04-15",
				"Partner Order Number": "114-1970620-5534667-return",
				"Total": "-1,998.00"
			},
			{
				"Invoice": "264350",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "COLEY, WILMA",
				"Invoice Date": "2019-04-16",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "264708",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "BUONOMO, CHRISTINA",
				"Invoice Date": "2019-04-18",
				"Partner Order Number": "113-0925288-2556239-return",
				"Total": "-152.16"
			},
			{
				"Invoice": "265868",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.COM",
				"Customer Name": "HALL, SAM",
				"Invoice Date": "2019-04-23",
				"Partner Order Number": "114-3394008-7386640-Ret",
				"Total": "0.00"
			},
			{
				"Invoice": "266884",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "EBAY Parts",
				"Customer Name": "AYBAR, MAYRA",
				"Invoice Date": "2019-04-28",
				"Partner Order Number": "778-Return",
				"Total": "-106.20"
			},
			{
				"Invoice": "267437",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "BRIAN, OQUINN",
				"Invoice Date": "2019-04-30",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "267546",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CARRIER CLAIMS, FEDEX",
				"Invoice Date": "2019-05-01",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "267620",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "HAAS, ADAM",
				"Invoice Date": "2019-05-01",
				"Partner Order Number": "234544-replace",
				"Total": "0.00"
			},
			{
				"Invoice": "267815",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "SWAIN, ALAN",
				"Invoice Date": "2019-05-02",
				"Partner Order Number": "",
				"Total": "11,299.95"
			},
			{
				"Invoice": "268221",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Walmart.com",
				"Customer Name": "GORDON, TERRY",
				"Invoice Date": "2019-05-05",
				"Partner Order Number": "1794875880065-Return",
				"Total": "-686.09"
			},
			{
				"Invoice": "268951",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.com",
				"Customer Name": ", GEORGE",
				"Invoice Date": "2019-05-09",
				"Partner Order Number": "112-7974952-7764201-Return",
				"Total": "-1,249.63"
			},
			{
				"Invoice": "268993",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "NAPOLITANO, CHRISTIAN",
				"Invoice Date": "2019-05-09",
				"Partner Order Number": "112-7078406-3039441-return",
				"Total": "-369.74"
			},
			{
				"Invoice": "269084",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "NAPOLITANO, CHRISTIAN",
				"Invoice Date": "2019-05-10",
				"Partner Order Number": "112-7078406-3039441-replacment",
				"Total": "0.00"
			},
			{
				"Invoice": "269586",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "WALTON, MICHAEL",
				"Invoice Date": "2019-05-13",
				"Partner Order Number": "112-4623061-9206655-return",
				"Total": "-681.37"
			},
			{
				"Invoice": "269666",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "INVOICE, TEST",
				"Invoice Date": "2019-05-14",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "269674",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "INVOICE, TEST",
				"Invoice Date": "2019-05-14",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "269684",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.com",
				"Customer Name": "INVOICE, TEST",
				"Invoice Date": "2019-05-14",
				"Partner Order Number": "123456789",
				"Total": "0.00"
			},
			{
				"Invoice": "269803",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "EBAY.COM",
				"Customer Name": "CLAYBON, STANLEY",
				"Invoice Date": "2019-05-14",
				"Partner Order Number": "282812088790-1989053884018-Refund",
				"Total": "-134.30"
			},
			{
				"Invoice": "269844",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "INVOICE, TEST",
				"Invoice Date": "2019-05-15",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "269846",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "INVOICE, TEST",
				"Invoice Date": "2019-05-15",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "269847",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "INVOICE, TEST",
				"Invoice Date": "2019-05-15",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "269916",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "ASSOCIATES, NATIONAL VETERINARY",
				"Invoice Date": "2019-05-15",
				"Partner Order Number": "112-1519612-0325061-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "269940",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "SCHMIDT, AARON",
				"Invoice Date": "2019-05-15",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "270056",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CART TOWN, JAY KIM",
				"Invoice Date": "2019-05-16",
				"Partner Order Number": "",
				"Total": "30,549.75"
			},
			{
				"Invoice": "270200",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "rakuten.com",
				"Customer Name": "ADDURI, TANVI",
				"Invoice Date": "2019-05-17",
				"Partner Order Number": "0047243-190513-1747147592-return",
				"Total": "-89.00"
			},
			{
				"Invoice": "270204",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "LAURSEN, BRAD",
				"Invoice Date": "2019-05-17",
				"Partner Order Number": "3794958124754-return",
				"Total": "-2,077.73"
			},
			{
				"Invoice": "270262",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "MUSTEA, DACIAN",
				"Invoice Date": "2019-05-17",
				"Partner Order Number": "111-4462512-9361808-RETURN",
				"Total": "0.00"
			},
			{
				"Invoice": "270280",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.com",
				"Customer Name": "SLEINERS, MIKUS",
				"Invoice Date": "2019-05-17",
				"Partner Order Number": "111-3245009-7971438-Return",
				"Total": "-521.13"
			},
			{
				"Invoice": "270602",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "ALLEN, SCOTT",
				"Invoice Date": "2019-05-20",
				"Partner Order Number": "",
				"Total": "2,000.00"
			},
			{
				"Invoice": "270752",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "ebayparts",
				"Customer Name": "RUNKLES, IAN",
				"Invoice Date": "2019-05-21",
				"Partner Order Number": "796-refund",
				"Total": "-16.95"
			},
			{
				"Invoice": "270778",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.com",
				"Customer Name": "DORETY, IAN",
				"Invoice Date": "2019-05-21",
				"Partner Order Number": "112-0951037-5477003-Return",
				"Total": "-635.78"
			},
			{
				"Invoice": "270781",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Sears.com",
				"Customer Name": "STROUP, BOB",
				"Invoice Date": "2019-05-21",
				"Partner Order Number": "2080380-replacement-Return",
				"Total": "0.00"
			},
			{
				"Invoice": "270930",
				"Invoice Type": "Walts.com Sale In State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "WEINBURGER, TONY",
				"Invoice Date": "2019-05-21",
				"Partner Order Number": "",
				"Total": "4,053.75"
			},
			{
				"Invoice": "270997",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walmart.com",
				"Customer Name": "PETRELLA, LAWRENCE",
				"Invoice Date": "2019-05-22",
				"Partner Order Number": "3795071062684-return",
				"Total": "0.00"
			},
			{
				"Invoice": "271041",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "FULLINWIDER, JAMIE",
				"Invoice Date": "2019-05-22",
				"Partner Order Number": "114-4992647-9023420-c",
				"Total": "-40.00"
			},
			{
				"Invoice": "271104",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "COVARELLI, GIANNA",
				"Invoice Date": "2019-05-22",
				"Partner Order Number": "111-0170652-0643418-return",
				"Total": "-1,078.12"
			},
			{
				"Invoice": "271199",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Walmart.com",
				"Customer Name": "ZHU, KEVIN",
				"Invoice Date": "2019-05-23",
				"Partner Order Number": "4791360717493-Return",
				"Total": "-696.99"
			},
			{
				"Invoice": "271240",
				"Invoice Type": "Marketplace In State",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "WARREN, GIL",
				"Invoice Date": "2019-05-23",
				"Partner Order Number": "270467-return",
				"Total": "0.00"
			},
			{
				"Invoice": "271439",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walmart.com",
				"Customer Name": "SILBERNAGEL, MICHAEL",
				"Invoice Date": "2019-05-24",
				"Partner Order Number": "1795132872752-concession",
				"Total": "30.00"
			},
			{
				"Invoice": "271508",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walmart.com",
				"Customer Name": "HAFEEZ, JAMAL",
				"Invoice Date": "2019-05-24",
				"Partner Order Number": "1795050545513-return",
				"Total": "-744.39"
			},
			{
				"Invoice": "271573",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "SHEFFIELD, JASON B",
				"Invoice Date": "2019-05-25",
				"Partner Order Number": "111-7436107-0345069-return",
				"Total": "0.00"
			},
			{
				"Invoice": "271851",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "KIM, KYENG TAE",
				"Invoice Date": "2019-05-27",
				"Partner Order Number": "69229",
				"Total": "1,466.99"
			},
			{
				"Invoice": "271969",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "SMITHHART, DAVID",
				"Invoice Date": "2019-05-28",
				"Partner Order Number": "112-5708267-3033051-return",
				"Total": "0.00"
			},
			{
				"Invoice": "271979",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Walts.com",
				"Customer Name": "GILL, EDITH",
				"Invoice Date": "2019-05-28",
				"Partner Order Number": "68781-Return",
				"Total": "-4,031.19"
			},
			{
				"Invoice": "272007",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "WAKEFIELD, BRENDA",
				"Invoice Date": "2019-05-28",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "272360",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "ULM, ERICH",
				"Invoice Date": "2019-05-29",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "272458",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.com",
				"Customer Name": "MOORE, JACINDA",
				"Invoice Date": "2019-05-30",
				"Partner Order Number": "113-2207287-5252239-Return",
				"Total": "-1,089.99"
			},
			{
				"Invoice": "272561",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "JENNINGS, ANTHONY",
				"Invoice Date": "2019-05-31",
				"Partner Order Number": "113-5351182-6007435-return",
				"Total": "0.00"
			},
			{
				"Invoice": "272572",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "ebay.com",
				"Customer Name": "PICHON, SHMUEL",
				"Invoice Date": "2019-05-31",
				"Partner Order Number": "183620985242-1970086093008-Return",
				"Total": "-1,548.59"
			},
			{
				"Invoice": "272583",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "TV, WALTS",
				"Invoice Date": "2019-05-31",
				"Partner Order Number": "",
				"Total": "5,621.16"
			},
			{
				"Invoice": "272696",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "ZHUKOV, FREIGHTREADYCOM",
				"Invoice Date": "2019-05-31",
				"Partner Order Number": "112-2965151-9500218-return",
				"Total": "-2,886.31"
			},
			{
				"Invoice": "272795",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walmart.com",
				"Customer Name": "MACAULEY, TAMARA",
				"Invoice Date": "2019-06-02",
				"Partner Order Number": "3795225090969-C",
				"Total": "-100.00"
			},
			{
				"Invoice": "272818",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "SMITHHART, DAVID",
				"Invoice Date": "2019-06-02",
				"Partner Order Number": "112-5708267-3033051-replace",
				"Total": "0.00"
			},
			{
				"Invoice": "272827",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "HAGEN, GAIL",
				"Invoice Date": "2019-06-02",
				"Partner Order Number": "112-9216963-6025047-RETURN",
				"Total": "-148.39"
			},
			{
				"Invoice": "272834",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "HUNTER, JOCELYN",
				"Invoice Date": "2019-06-02",
				"Partner Order Number": "69579",
				"Total": "323.25"
			},
			{
				"Invoice": "272879",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walmart.com",
				"Customer Name": "JACKSON, CHERYL",
				"Invoice Date": "2019-06-03",
				"Partner Order Number": "4791473421370-return",
				"Total": "-597.94"
			},
			{
				"Invoice": "272951",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.com",
				"Customer Name": "TLUCZEK, JOHN",
				"Invoice Date": "2019-06-03",
				"Partner Order Number": "112-9276859-2819463-Return",
				"Total": "-2,809.52"
			},
			{
				"Invoice": "273094",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "PRESTON, JEFF",
				"Invoice Date": "2019-06-03",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "273138",
				"Invoice Type": "Walts.com Sale In State",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "NOBODY, JOE",
				"Invoice Date": "2019-06-04",
				"Partner Order Number": "69594",
				"Total": "1,584.74"
			},
			{
				"Invoice": "273229",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walmart.com",
				"Customer Name": "DELAY, CINDY",
				"Invoice Date": "2019-06-04",
				"Partner Order Number": "2791452924000-RETURN",
				"Total": "-2,519.99"
			},
			{
				"Invoice": "273252",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "SCHIAVO, DAVID",
				"Invoice Date": "2019-06-05",
				"Partner Order Number": "114-4473535-3656233-return",
				"Total": "-2,822.71"
			},
			{
				"Invoice": "273340",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "MOORE, DENISE",
				"Invoice Date": "2019-06-05",
				"Partner Order Number": "68893-return",
				"Total": "-649.99"
			},
			{
				"Invoice": "273476",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walmart.com",
				"Customer Name": "REYNOLDS, MALETA",
				"Invoice Date": "2019-06-06",
				"Partner Order Number": "2791483709921-c",
				"Total": "-100.00"
			},
			{
				"Invoice": "273488",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walmart.com",
				"Customer Name": "CRICKENBERGER, JON",
				"Invoice Date": "2019-06-06",
				"Partner Order Number": "1795194323920-return",
				"Total": "-2,096.90"
			},
			{
				"Invoice": "273504",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "KALYANASUNDARAM, ARVIND",
				"Invoice Date": "2019-06-06",
				"Partner Order Number": "69430-return",
				"Total": "-996.99"
			},
			{
				"Invoice": "273505",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "STUMPF, JARAMIE",
				"Invoice Date": "2019-06-06",
				"Partner Order Number": "259004-Return",
				"Total": "-69.98"
			},
			{
				"Invoice": "273544",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "LEE, KENNETH",
				"Invoice Date": "2019-06-07",
				"Partner Order Number": "69443-return",
				"Total": "-996.99"
			},
			{
				"Invoice": "273548",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "GREEN, JABARI",
				"Invoice Date": "2019-06-07",
				"Partner Order Number": "114-5931905-7255406-return",
				"Total": "-2,288.87"
			},
			{
				"Invoice": "273552",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "PATTERSON, MICHAEL",
				"Invoice Date": "2019-06-07",
				"Partner Order Number": "68819-return",
				"Total": "-3,484.72"
			},
			{
				"Invoice": "273570",
				"Invoice Type": "Marketplace In State",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "ROSALES, JESUS",
				"Invoice Date": "2019-06-07",
				"Partner Order Number": "112-1337219-5538632-c",
				"Total": "-75.00"
			},
			{
				"Invoice": "273586",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Walmart.com",
				"Customer Name": "MCKINNON, LISA",
				"Invoice Date": "2019-06-07",
				"Partner Order Number": "2791360763972-Return",
				"Total": "-46.90"
			},
			{
				"Invoice": "273588",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walmart.com",
				"Customer Name": "SIMPSON, TATE",
				"Invoice Date": "2019-06-07",
				"Partner Order Number": "4791473538811-return",
				"Total": "-818.42"
			},
			{
				"Invoice": "273683",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "ebay.,com",
				"Customer Name": "LICHTENBERGER, BRIAN",
				"Invoice Date": "2019-06-07",
				"Partner Order Number": "283379251675-1990490261018-return",
				"Total": "-26.99"
			},
			{
				"Invoice": "273692",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "OPIPARI, MARK",
				"Invoice Date": "2019-06-07",
				"Partner Order Number": "271809-return",
				"Total": "-996.99"
			},
			{
				"Invoice": "273693",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": ",",
				"Invoice Date": "2019-06-07",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "273695",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "PARISH, CASIE J",
				"Invoice Date": "2019-06-07",
				"Partner Order Number": "113-5135597-3493868-return",
				"Total": "-2,886.85"
			},
			{
				"Invoice": "273739",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "JUREKA, BRAD",
				"Invoice Date": "2019-06-08",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "273740",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "JUREKA, BRAD",
				"Invoice Date": "2019-06-08",
				"Partner Order Number": "69646",
				"Total": "4,184.51"
			},
			{
				"Invoice": "273765",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walmart.com",
				"Customer Name": "BHULLAR, NEETU",
				"Invoice Date": "2019-06-08",
				"Partner Order Number": "3795173589944-return",
				"Total": "-1,716.09"
			},
			{
				"Invoice": "273882",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "MOSKALEVA, OLGA",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "69037-return",
				"Total": "-1,557.06"
			},
			{
				"Invoice": "273891",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.COM",
				"Customer Name": "JACK, ASHLEY",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "112-2994313-2268235-Return",
				"Total": "0.00"
			},
			{
				"Invoice": "273896",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "SHIN, KI",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "69187-Return",
				"Total": "-1,580.30"
			},
			{
				"Invoice": "273912",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "FULTON, ROBERT",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "",
				"Total": "2,067.99"
			},
			{
				"Invoice": "273917",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "STEVENSON, J",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "113-0108656-0780226-Return",
				"Total": "-149.79"
			},
			{
				"Invoice": "273957",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Walts.com",
				"Customer Name": "BOLEN, ROBERT",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "69585-return",
				"Total": "-579.99"
			},
			{
				"Invoice": "273969",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "CARR, JEFF",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "113-6075955-9012223-Return",
				"Total": "-58.00"
			},
			{
				"Invoice": "273973",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": ",",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "",
				"Total": "3,242.93"
			},
			{
				"Invoice": "273982",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "NIES, DUSTIN",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "112-2922286-9456205-Return",
				"Total": "-1.09"
			},
			{
				"Invoice": "273988",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "BELLACH, TY",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "113-6497700-5875404-Return",
				"Total": "-632.19"
			},
			{
				"Invoice": "273990",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "LORA, ROBERT",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "112-9083924-6293850-Return",
				"Total": "-341.99"
			},
			{
				"Invoice": "273994",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "amazon.com",
				"Customer Name": "AUSTIN, CHARLES",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "112-2290470-4880220-Return",
				"Total": "-727.99"
			},
			{
				"Invoice": "274001",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CART TOWN, JAY KIM",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "",
				"Total": "7,911.81"
			},
			{
				"Invoice": "274002",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CART TOWN, JAY KIM",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "",
				"Total": "14,696.30"
			},
			{
				"Invoice": "274005",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Rakuten.com",
				"Customer Name": "VEGA, TAMARA",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "0047243-190526-0100337980-Return",
				"Total": "0.00"
			},
			{
				"Invoice": "274136",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "WINSTON, JOSH",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "68930-Return",
				"Total": "-1,482.94"
			},
			{
				"Invoice": "274138",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Walmart.com",
				"Customer Name": "CLAIMS, WALMART",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "2790527586652-RI",
				"Total": "1,680.48"
			},
			{
				"Invoice": "274153",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CARRIER CLAIMS, FEDEX",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "",
				"Total": "2,205.00"
			},
			{
				"Invoice": "274166",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.com",
				"Customer Name": "MORGAN BROWN,",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "114-7660428-6990644-Return",
				"Total": "-1,069.37"
			},
			{
				"Invoice": "274167",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CART TOWN, JAY KIM",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "",
				"Total": "6,208.99"
			},
			{
				"Invoice": "274169",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "SILLIK, DAVID",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "274180",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "ebay.com",
				"Customer Name": "GLOVER, JERRY",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "183836376450-1975038807008-Return",
				"Total": "-29.99"
			},
			{
				"Invoice": "274181",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "BLUNIER, NATHAN",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "274192",
				"Invoice Type": "Chat Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "URBANCIC, CYRIL",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "69610-Return",
				"Total": "0.00"
			},
			{
				"Invoice": "274197",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.COM",
				"Customer Name": "ZUG, DIANE H",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "113-1066866-1476206-r",
				"Total": "-196.56"
			},
			{
				"Invoice": "274198",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "BLASCHIK, OWEN J",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "274199",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.COM",
				"Customer Name": "SALIMI, BIJAN",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "113-6330675-3890653-r",
				"Total": "-118.94"
			},
			{
				"Invoice": "274200",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.COM",
				"Customer Name": "SLEEBA, SHIBU",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "111-8146783-8197866-r",
				"Total": "0.00"
			},
			{
				"Invoice": "274201",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "URBANCIC, CYRIL",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "",
				"Total": "-2,585.38"
			},
			{
				"Invoice": "274204",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "Amazon.COM",
				"Customer Name": "THIRD FEDERAL SAVINGS, AJ EDWARDS",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "114-9163420-5267411-r",
				"Total": "-801.48"
			},
			{
				"Invoice": "274207",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "CART TOWN, JAY KIM",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "274209",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "ebay.com",
				"Customer Name": "BOCHSLER, DUSTIN",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "183836376450-1975090260008-return",
				"Total": "-29.99"
			},
			{
				"Invoice": "274276",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "GOLDBERG, SHELDON",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "274277",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "GOLDBERG, SHELDON",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "",
				"Total": "0.00"
			},
			{
				"Invoice": "274322",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "newegg.com",
				"Customer Name": "FERRARO, BARRY",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "448146372-return",
				"Total": "-1,172.65"
			},
			{
				"Invoice": "274333",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "ECKSTEIN, ELLEN & DAVID",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "69202-Return",
				"Total": "0.00"
			},
			{
				"Invoice": "274347",
				"Invoice Type": "Walts.com Sale In State",
				"Order Status": "NEW ORDER",
				"Partner": "walts.com",
				"Customer Name": "MARCOLINI, JULIO",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "69680",
				"Total": "4,862.33"
			},
			{
				"Invoice": "274377",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Partner": "",
				"Customer Name": "DIST, ALLFIRE EQUIPMENT",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "",
				"Total": "7,315.80"
			},
			{
				"Invoice": "273733",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Paid Out Of Stock",
				"Partner": "walmart.com",
				"Customer Name": "JUREKA, BRAD",
				"Invoice Date": "2019-06-08",
				"Partner Order Number": "4791606987829",
				"Total": "4,304.41"
			},
			{
				"Invoice": "273935",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "Payment Verification",
				"Partner": "walts.com",
				"Customer Name": "BURPO, RYAN",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "69665",
				"Total": "559.99"
			},
			{
				"Invoice": "274342",
				"Invoice Type": "Marketplace In State",
				"Order Status": "Payment Verification",
				"Partner": "ebay",
				"Customer Name": "GRIGORIEFF, DANTE",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "283472174827-2000111633018",
				"Total": "104.13"
			},
			{
				"Invoice": "274344",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "Payment Verification",
				"Partner": "walts.com",
				"Customer Name": "KING, JAMES",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "69678",
				"Total": "2,969.80"
			},
			{
				"Invoice": "274367",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Payment Verification",
				"Partner": "ebay",
				"Customer Name": "STONEBURNER, CHAD",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "283506365385-2000168951018",
				"Total": "56.59"
			},
			{
				"Invoice": "274329",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "PRS Walts Parcel",
				"Partner": "ebayparts.com",
				"Customer Name": "EUI SEOP KR0146143982, JEONG",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "810",
				"Total": "239.95"
			},
			{
				"Invoice": "274330",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "PRS Walts Parcel",
				"Partner": "ebayparts.com",
				"Customer Name": "BATEMAN, ROCKY",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "811",
				"Total": "122.94"
			},
			{
				"Invoice": "274379",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "PRS Walts Parcel",
				"Partner": "Amazon",
				"Customer Name": "SCZUBLEWSKI, MARK",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "111-1605500-6540218",
				"Total": "133.93"
			},
			{
				"Invoice": "273498",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "ENGINEERING DEPT, 14TH FLOOR, JAMES LANEY",
				"Invoice Date": "2019-06-06",
				"Partner Order Number": "113-4175319-0470651",
				"Total": "2,697.99"
			},
			{
				"Invoice": "273852",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "QC COMPLETE",
				"Partner": "walts.com",
				"Customer Name": "TALWAR, ASHEESH",
				"Invoice Date": "2019-06-09",
				"Partner Order Number": "69653",
				"Total": "2,800.00"
			},
			{
				"Invoice": "273893",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "GARRY, RUSSELL",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "113-2382691-5239419",
				"Total": "2,845.83"
			},
			{
				"Invoice": "273921",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "QC COMPLETE",
				"Partner": "walts.com",
				"Customer Name": "SKELTON, ORRY",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "69663",
				"Total": "2,991.80"
			},
			{
				"Invoice": "274187",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "QC COMPLETE",
				"Partner": "",
				"Customer Name": "URBANCIC, CYRIL",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "",
				"Total": "2,585.38"
			},
			{
				"Invoice": "274208",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "SMITH, LESTER",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "113-3977342-3282604",
				"Total": "40.55"
			},
			{
				"Invoice": "274210",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "ebay",
				"Customer Name": "LU, WEIJIE",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "283506363886-1999826652018",
				"Total": "104.00"
			},
			{
				"Invoice": "274211",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "ALMAN, LAUREN",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "112-9065803-5481811",
				"Total": "687.48"
			},
			{
				"Invoice": "274212",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "VANDERVINNE, DAVE",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "113-5399853-1238651",
				"Total": "180.97"
			},
			{
				"Invoice": "274213",
				"Invoice Type": "Marketplace In State",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "VIDEOPOSCWABE, PERFORMANCE AUDIO",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "113-4889440-0532212",
				"Total": "131.74"
			},
			{
				"Invoice": "274217",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "LAVALLEUR, MATTHEW RYAN",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "113-3369719-4318643",
				"Total": "647.00"
			},
			{
				"Invoice": "274218",
				"Invoice Type": "Marketplace In State",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "BAGLEY, SHREE",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "112-5919001-3123438",
				"Total": "41.05"
			},
			{
				"Invoice": "274225",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "walmart.com",
				"Customer Name": "HANSEN, CARL",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "3795327843825",
				"Total": "776.91"
			},
			{
				"Invoice": "274226",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "rakuten",
				"Customer Name": "CHEN, WALT",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "0047243-190611-2153124727",
				"Total": "324.82"
			},
			{
				"Invoice": "274228",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "walmart.com",
				"Customer Name": "MOUNT, CLYDE",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "2791637871317",
				"Total": "1,488.00"
			},
			{
				"Invoice": "274234",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "SHAH, NEIL",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "113-1738541-9682666",
				"Total": "74.29"
			},
			{
				"Invoice": "274237",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "walmart.com",
				"Customer Name": "WINDHAM, NEDY",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "3795327856010",
				"Total": "784.38"
			},
			{
				"Invoice": "274278",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "SCOVILLE, CONRAD",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "113-2297334-5067466",
				"Total": "74.36"
			},
			{
				"Invoice": "274279",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "sears",
				"Customer Name": "REEF, GIA",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "3105358",
				"Total": "178.51"
			},
			{
				"Invoice": "274282",
				"Invoice Type": "Marketplace In State",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "BELL, JARED",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "112-0332441-6777047",
				"Total": "1,077.63"
			},
			{
				"Invoice": "274285",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "ELLIS, TAYLOR",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "111-8344542-7489059",
				"Total": "179.84"
			},
			{
				"Invoice": "274287",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "newegg",
				"Customer Name": "NASIR, JAHANGIR",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "270043809",
				"Total": "1,591.04"
			},
			{
				"Invoice": "274293",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "MUSANTE, ANTHONY",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "112-0198277-7287401",
				"Total": "133.93"
			},
			{
				"Invoice": "274294",
				"Invoice Type": "Marketplace In State",
				"Order Status": "QC COMPLETE",
				"Partner": "walmart.com",
				"Customer Name": "REEVES, JEFFREY",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "2791647930524",
				"Total": "591.63"
			},
			{
				"Invoice": "274295",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "PETTAY, ADAM",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "114-7575620-3548222",
				"Total": "40.68"
			},
			{
				"Invoice": "274296",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "walmart.com",
				"Customer Name": "RACKLEY, MARDELLE",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "2791647938116",
				"Total": "41.39"
			},
			{
				"Invoice": "274297",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "HOLLAND, GEORGE",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "114-1690802-9757831",
				"Total": "747.86"
			},
			{
				"Invoice": "274300",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "rakuten",
				"Customer Name": "LI, JOHNNY",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "0047243-190611-0229525525",
				"Total": "323.90"
			},
			{
				"Invoice": "274302",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "KOBERT, JENN PEARSALL",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "112-7056671-9495461",
				"Total": "4,352.36"
			},
			{
				"Invoice": "274303",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "WELCH, RONALD W",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "113-4814826-1181059",
				"Total": "2,895.83"
			},
			{
				"Invoice": "274304",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "walmart.com",
				"Customer Name": "JONES, TERRY",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "2791647954945",
				"Total": "2,642.99"
			},
			{
				"Invoice": "274305",
				"Invoice Type": "Marketplace In State",
				"Order Status": "QC COMPLETE",
				"Partner": "newegg",
				"Customer Name": "MARDER, DAWNA",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "284035019",
				"Total": "755.62"
			},
			{
				"Invoice": "274309",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "TABER, KRISTIN",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "114-2916993-3489853",
				"Total": "133.93"
			},
			{
				"Invoice": "274311",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "walmart.com",
				"Customer Name": "CASTILLO, RICHARD",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "4791647966392",
				"Total": "433.81"
			},
			{
				"Invoice": "274313",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "DESERTSPRING, DAVID",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "114-1887038-2125867",
				"Total": "97.81"
			},
			{
				"Invoice": "274317",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "INC, MADDY",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "112-8701907-2665008",
				"Total": "549.99"
			},
			{
				"Invoice": "274321",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "NICHOLS, ASHLEY",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "114-0819714-8674656",
				"Total": "3,092.75"
			},
			{
				"Invoice": "274323",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "walmart.com",
				"Customer Name": "JEDLINSKI, ROB",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "1795337980914",
				"Total": "627.38"
			},
			{
				"Invoice": "274332",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "TAYLOR, BRIAN",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "113-9825593-0354664",
				"Total": "535.78"
			},
			{
				"Invoice": "274334",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "WEISS, RICHARD C",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "112-9371098-6789047",
				"Total": "102.85"
			},
			{
				"Invoice": "274335",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "QC COMPLETE",
				"Partner": "walts.com",
				"Customer Name": "SIMSEK, ERDOGAN",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "274335",
				"Total": "1,886.04"
			},
			{
				"Invoice": "274341",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "NELSON, SCOTT",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "114-4332471-4114625",
				"Total": "534.09"
			},
			{
				"Invoice": "274345",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "LAPPE, ALICE",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "114-6666124-1754623",
				"Total": "131.19"
			},
			{
				"Invoice": "274346",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "Amazon",
				"Customer Name": "DUMAIS, STEVE",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "113-6851509-9429851",
				"Total": "1,077.35"
			},
			{
				"Invoice": "274351",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "QC COMPLETE",
				"Partner": "walmart.com",
				"Customer Name": "FRKETICH, VIRGINIA",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "4791648098148",
				"Total": "156.02"
			},
			{
				"Invoice": "274336",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Shipped",
				"Partner": "Amazon",
				"Customer Name": "GERKEN, AMY",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "114-9069420-8658652",
				"Total": "0.00"
			},
			{
				"Invoice": "274366",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Shipping FAQ",
				"Partner": "Amazon",
				"Customer Name": "WOO, JOHN",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "114-0857993-4833058",
				"Total": "366.22"
			},
			{
				"Invoice": "274378",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Shipping FAQ",
				"Partner": "Amazon",
				"Customer Name": "MASON, JEFFREY",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "111-6303629-0518611",
				"Total": "2,895.83"
			},
			{
				"Invoice": "273894",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Tracking Pending",
				"Partner": "Amazon.COM",
				"Customer Name": "JACK, ASHLEY",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "112-2994313-2268235-Replacement",
				"Total": "0.00"
			},
			{
				"Invoice": "274000",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Tracking Pending",
				"Partner": "ebay",
				"Customer Name": "THORSON, TERRY",
				"Invoice Date": "2019-06-10",
				"Partner Order Number": "283506471230-1999467812018",
				"Total": "678.34"
			},
			{
				"Invoice": "274176",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Tracking Pending",
				"Partner": "rakuten",
				"Customer Name": "ADAMO, JARED",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "0047243-190611-1730007686",
				"Total": "1,915.98"
			},
			{
				"Invoice": "274202",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Tracking Pending",
				"Partner": "Amazon",
				"Customer Name": "URBAN, SEAN",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "113-1500605-8353816",
				"Total": "1,110.87"
			},
			{
				"Invoice": "274224",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Tracking Pending",
				"Partner": "Amazon",
				"Customer Name": "SEGOVIA, NELSON",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "111-8754065-7428202",
				"Total": "167.99"
			},
			{
				"Invoice": "274298",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Tracking Pending",
				"Partner": "Amazon",
				"Customer Name": "BIGGS, WILLIAM",
				"Invoice Date": "2019-06-11",
				"Partner Order Number": "113-4872838-7848222",
				"Total": "179.41"
			},
			{
				"Invoice": "274331",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Tracking Pending",
				"Partner": "Amazon",
				"Customer Name": "BRODEUR, JEFFREY",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "114-2854610-1776230",
				"Total": "356.98"
			},
			{
				"Invoice": "274350",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "Tracking Pending",
				"Partner": "Amazon",
				"Customer Name": "ANDREA, RICHARD",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "114-9196890-0501003",
				"Total": "179.41"
			},
			{
				"Invoice": "273508",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "WAITING PACK",
				"Partner": "walmart.com",
				"Customer Name": "MARKHAM, BARBARA",
				"Invoice Date": "2019-06-06",
				"Partner Order Number": "2791596566746",
				"Total": "255.30"
			},
			{
				"Invoice": "274348",
				"Invoice Type": "Marketplace Sale",
				"Order Status": "WAITING QC",
				"Partner": "walmart.com",
				"Customer Name": "RUHLE, CHAD",
				"Invoice Date": "2019-06-12",
				"Partner Order Number": "2791648090296",
				"Total": "3,484.72"
			}
		]';
    }

    protected function _getOpenNonPartnerOrdersMockJson()
    {
        return '
        [
			{
				"Invoice": "270056",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Richard",
				"Customer Name": "CART TOWN, JAY KIM",
				"Invoice Date": "2019-05-16",
				"Total": "30,549.75"
			},
			{
				"Invoice": "271984",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Richard",
				"Customer Name": "CART TOWN, JAY KIM",
				"Invoice Date": "2019-05-28",
				"Total": "25,983.21"
			},
			{
				"Invoice": "271985",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Richard",
				"Customer Name": "CART TOWN, JAY KIM",
				"Invoice Date": "2019-05-28",
				"Total": "17,481.55"
			},
			{
				"Invoice": "272286",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Richard",
				"Customer Name": "CART TOWN, JAY KIM",
				"Invoice Date": "2019-05-29",
				"Total": "101,500.00"
			},
			{
				"Invoice": "273693",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "House",
				"Customer Name": ",",
				"Invoice Date": "2019-06-07",
				"Total": "0.00"
			},
			{
				"Invoice": "273973",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Richard",
				"Customer Name": ",",
				"Invoice Date": "2019-06-10",
				"Total": "3,242.93"
			},
			{
				"Invoice": "274001",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Richard",
				"Customer Name": "CART TOWN, JAY KIM",
				"Invoice Date": "2019-06-10",
				"Total": "5,669.01"
			},
			{
				"Invoice": "274002",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Richard",
				"Customer Name": "CART TOWN, JAY KIM",
				"Invoice Date": "2019-06-10",
				"Total": "12,375.80"
			},
			{
				"Invoice": "274167",
				"Invoice Type": "B2B Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Richard",
				"Customer Name": "CART TOWN, JAY KIM",
				"Invoice Date": "2019-06-11",
				"Total": "6,208.99"
			},
			{
				"Invoice": "250007",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "House",
				"Customer Name": "WHITE, CHRIS",
				"Invoice Date": "2018-12-29",
				"Total": "0.00"
			},
			{
				"Invoice": "250201",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "House",
				"Customer Name": "WHITE, CHRIS",
				"Invoice Date": "2018-12-31",
				"Total": "0.00"
			},
			{
				"Invoice": "250549",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Steve",
				"Customer Name": "TACKETT, BRAD",
				"Invoice Date": "2019-01-04",
				"Total": "1,496.23"
			},
			{
				"Invoice": "264372",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "ESCAMILLO, TONY",
				"Invoice Date": "2019-04-16",
				"Total": "0.00"
			},
			{
				"Invoice": "265391",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Miles",
				"Customer Name": "PLOEN, DON",
				"Invoice Date": "2019-04-21",
				"Total": "-702.65"
			},
			{
				"Invoice": "266423",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Miles",
				"Customer Name": "FELDMAN, MARK",
				"Invoice Date": "2019-04-25",
				"Total": "-107.02"
			},
			{
				"Invoice": "268023",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "MARIANO, JOHN",
				"Invoice Date": "2019-05-03",
				"Total": "-215.50"
			},
			{
				"Invoice": "268425",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Nate",
				"Customer Name": "NATALIE, BYRD",
				"Invoice Date": "2019-05-06",
				"Total": "75.67"
			},
			{
				"Invoice": "268684",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Tom",
				"Customer Name": "THOMPSON, BRAD",
				"Invoice Date": "2019-05-07",
				"Total": "27.02"
			},
			{
				"Invoice": "270493",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "House",
				"Customer Name": "BOURNE, LOGAN",
				"Invoice Date": "2019-05-19",
				"Total": "108.10"
			},
			{
				"Invoice": "270772",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Bob",
				"Customer Name": "MOXY HOTEL, JOSE LUIS",
				"Invoice Date": "2019-05-21",
				"Total": "254.45"
			},
			{
				"Invoice": "271866",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "LOWE, MATT",
				"Invoice Date": "2019-05-27",
				"Total": "68.00"
			},
			{
				"Invoice": "271870",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "House",
				"Customer Name": "LOWE, MATTHEW",
				"Invoice Date": "2019-05-27",
				"Total": "0.00"
			},
			{
				"Invoice": "272367",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Miles",
				"Customer Name": "FARRELL, BART",
				"Invoice Date": "2019-05-29",
				"Total": "1,581.49"
			},
			{
				"Invoice": "272824",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "TEGEN, THIERRY",
				"Invoice Date": "2019-06-02",
				"Total": "4,253.74"
			},
			{
				"Invoice": "272933",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Miles",
				"Customer Name": "WEBER, BERNIE",
				"Invoice Date": "2019-06-03",
				"Total": "1,567.44"
			},
			{
				"Invoice": "273829",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "SLOAN, GERMAINE",
				"Invoice Date": "2019-06-09",
				"Total": "541.04"
			},
			{
				"Invoice": "274007",
				"Invoice Type": "Carry Out Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Richard",
				"Customer Name": "GHABBANI, DANIEL",
				"Invoice Date": "2019-06-10",
				"Total": "156.20"
			},
			{
				"Invoice": "261231",
				"Invoice Type": "Delivery Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ethan",
				"Customer Name": "KOCH, KEN",
				"Invoice Date": "2019-03-27",
				"Total": "3,489.56"
			},
			{
				"Invoice": "266876",
				"Invoice Type": "Delivery Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "ZENDEJAS, DANIEL JR",
				"Invoice Date": "2019-04-28",
				"Total": "4,049.69"
			},
			{
				"Invoice": "272406",
				"Invoice Type": "Delivery Sale",
				"Order Status": "Local Sale",
				"Salesperson": "sears",
				"Customer Name": "RODRIGUEZ, GABRIEL",
				"Invoice Date": "2019-05-30",
				"Total": "1,068.98"
			},
			{
				"Invoice": "272831",
				"Invoice Type": "Delivery Sale",
				"Order Status": "Local Sale",
				"Salesperson": "Antonio",
				"Customer Name": "IWATAKE, LAURIE",
				"Invoice Date": "2019-06-02",
				"Total": "2,170.60"
			},
			{
				"Invoice": "272946",
				"Invoice Type": "Delivery Sale",
				"Order Status": "Local Sale",
				"Salesperson": "Amazon",
				"Customer Name": "CRANMER, RUSSELL",
				"Invoice Date": "2019-06-03",
				"Total": "3,251.60"
			},
			{
				"Invoice": "273482",
				"Invoice Type": "Delivery Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ed",
				"Customer Name": "HISAMOTO, ED",
				"Invoice Date": "2019-06-06",
				"Total": "555.86"
			},
			{
				"Invoice": "273750",
				"Invoice Type": "Delivery Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Richard",
				"Customer Name": ", MARK",
				"Invoice Date": "2019-06-08",
				"Total": "4,107.80"
			},
			{
				"Invoice": "273898",
				"Invoice Type": "Delivery Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "SCHMITT, AUSTIN",
				"Invoice Date": "2019-06-10",
				"Total": "405.38"
			},
			{
				"Invoice": "273919",
				"Invoice Type": "Delivery Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ed",
				"Customer Name": "HELIOTI, MARIA",
				"Invoice Date": "2019-06-10",
				"Total": "1,817.38"
			},
			{
				"Invoice": "273947",
				"Invoice Type": "Delivery Sale",
				"Order Status": "Local Sale",
				"Salesperson": "House",
				"Customer Name": "HANNA, HANK",
				"Invoice Date": "2019-06-10",
				"Total": "2,153.84"
			},
			{
				"Invoice": "273998",
				"Invoice Type": "Delivery Sale",
				"Order Status": "NEW ORDER",
				"Salesperson": "Richard",
				"Customer Name": "ANDING, RYAN",
				"Invoice Date": "2019-06-10",
				"Total": "3,904.94"
			},
			{
				"Invoice": "256938",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "TURNKEY TECHNOLGIES PHOENIX CITIZEN BANK, DAN HARTMAN",
				"Invoice Date": "2019-02-26",
				"Total": "1,474.40"
			},
			{
				"Invoice": "257778",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ethan",
				"Customer Name": "KOCH, KEN",
				"Invoice Date": "2019-03-03",
				"Total": "21,093.53"
			},
			{
				"Invoice": "258815",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ethan",
				"Customer Name": "SANDERS, FRANK",
				"Invoice Date": "2019-03-11",
				"Total": "3,387.69"
			},
			{
				"Invoice": "259331",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ethan",
				"Customer Name": "KOCH, KEN",
				"Invoice Date": "2019-03-14",
				"Total": "7,031.20"
			},
			{
				"Invoice": "259381",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ethan",
				"Customer Name": "KOCH, KEN",
				"Invoice Date": "2019-03-15",
				"Total": "11,695.03"
			},
			{
				"Invoice": "263843",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "WILLIAMS, CATHY / BRUCE",
				"Invoice Date": "2019-04-14",
				"Total": "32,795.50"
			},
			{
				"Invoice": "267217",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Miles",
				"Customer Name": "WESTALL, MARGARET",
				"Invoice Date": "2019-04-29",
				"Total": "0.00"
			},
			{
				"Invoice": "268759",
				"Invoice Type": "Installation",
				"Order Status": "Paid Out Of Stock",
				"Salesperson": "Ethan",
				"Customer Name": "KOCH, KEN",
				"Invoice Date": "2019-05-08",
				"Total": "7,830.59"
			},
			{
				"Invoice": "269572",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Miles",
				"Customer Name": "LARSON, CHRIS",
				"Invoice Date": "2019-05-13",
				"Total": "13,894.77"
			},
			{
				"Invoice": "270141",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Miles",
				"Customer Name": "NIMMONS, GERMAINE",
				"Invoice Date": "2019-05-16",
				"Total": "-54.00"
			},
			{
				"Invoice": "270197",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "BAILEY, LAURA",
				"Invoice Date": "2019-05-17",
				"Total": "13,891.27"
			},
			{
				"Invoice": "271019",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Bear",
				"Customer Name": "MONAHAN, TIM",
				"Invoice Date": "2019-05-22",
				"Total": "4,405.07"
			},
			{
				"Invoice": "271436",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "GOIN, PAUL",
				"Invoice Date": "2019-05-24",
				"Total": "0.00"
			},
			{
				"Invoice": "272010",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ethan",
				"Customer Name": "SANDERS, FRANK",
				"Invoice Date": "2019-05-28",
				"Total": "1,063.18"
			},
			{
				"Invoice": "272537",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "MATTOX, GARY",
				"Invoice Date": "2019-05-30",
				"Total": "0.00"
			},
			{
				"Invoice": "272917",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "JAMES, MELTON",
				"Invoice Date": "2019-06-03",
				"Total": "625.21"
			},
			{
				"Invoice": "273401",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ethan",
				"Customer Name": "KOCH, KEN",
				"Invoice Date": "2019-06-06",
				"Total": "3,042.22"
			},
			{
				"Invoice": "273411",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Bear",
				"Customer Name": "WARNER FAMILY PRACTICE, STEPHANIE",
				"Invoice Date": "2019-06-06",
				"Total": "0.00"
			},
			{
				"Invoice": "273494",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "NOTHUM, JOANN",
				"Invoice Date": "2019-06-06",
				"Total": "0.00"
			},
			{
				"Invoice": "273500",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "BHOOLA, SNEHAL",
				"Invoice Date": "2019-06-06",
				"Total": "0.00"
			},
			{
				"Invoice": "273549",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ethan",
				"Customer Name": "PALANTICK, ALLAN",
				"Invoice Date": "2019-06-07",
				"Total": "729.69"
			},
			{
				"Invoice": "273555",
				"Invoice Type": "Installation",
				"Order Status": "Local Sale",
				"Salesperson": "Veroncia",
				"Customer Name": "ROSALES, JESUS",
				"Invoice Date": "2019-06-07",
				"Total": "2,916.53"
			},
			{
				"Invoice": "273899",
				"Invoice Type": "Installation",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ethan",
				"Customer Name": "MATTHEW, RICHARD",
				"Invoice Date": "2019-06-10",
				"Total": "281.08"
			},
			{
				"Invoice": "261452",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "House",
				"Customer Name": "HENDLEY, STEVE",
				"Invoice Date": "2019-03-29",
				"Total": "3,457.22"
			},
			{
				"Invoice": "262177",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "HENRY, AUBREY",
				"Invoice Date": "2019-04-02",
				"Total": "3,900.00"
			},
			{
				"Invoice": "267815",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Miles",
				"Customer Name": "SWAIN, ALAN",
				"Invoice Date": "2019-05-02",
				"Total": "11,299.95"
			},
			{
				"Invoice": "272919",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Miles",
				"Customer Name": "CASTLE, JIM",
				"Invoice Date": "2019-06-03",
				"Total": "0.00"
			},
			{
				"Invoice": "273505",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "STUMPF, JARAMIE",
				"Invoice Date": "2019-06-06",
				"Total": "-69.98"
			},
			{
				"Invoice": "273739",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ed",
				"Customer Name": "JUREKA, BRAD",
				"Invoice Date": "2019-06-08",
				"Total": "0.00"
			},
			{
				"Invoice": "273912",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "FULTON, ROBERT",
				"Invoice Date": "2019-06-10",
				"Total": "2,067.99"
			},
			{
				"Invoice": "273975",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "Do Not Ship",
				"Salesperson": "Matt L",
				"Customer Name": "DAVIS, CHRISS",
				"Invoice Date": "2019-06-10",
				"Total": "810.00"
			},
			{
				"Invoice": "274169",
				"Invoice Type": "Phone Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Antonio",
				"Customer Name": "SILLIK, DAVID",
				"Invoice Date": "2019-06-11",
				"Total": "0.00"
			},
			{
				"Invoice": "268670",
				"Invoice Type": "Sales Call",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "SWEENEY, LINDA",
				"Invoice Date": "2019-05-07",
				"Total": "6,069.75"
			},
			{
				"Invoice": "272585",
				"Invoice Type": "Sales Call",
				"Order Status": "NEW ORDER",
				"Salesperson": "Miles",
				"Customer Name": "PUENTE, RAYMOND",
				"Invoice Date": "2019-05-31",
				"Total": "26,546.38"
			},
			{
				"Invoice": "272598",
				"Invoice Type": "Sales Call",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "NEWCOMB, LEE",
				"Invoice Date": "2019-05-31",
				"Total": "0.00"
			},
			{
				"Invoice": "272893",
				"Invoice Type": "Sales Call",
				"Order Status": "NEW ORDER",
				"Salesperson": "Miles",
				"Customer Name": "CHRISTENSEN, CALVIN",
				"Invoice Date": "2019-06-03",
				"Total": "0.00"
			},
			{
				"Invoice": "273551",
				"Invoice Type": "Sales Call",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ethan",
				"Customer Name": "STEBBINS, PAUL",
				"Invoice Date": "2019-06-07",
				"Total": "2,162.00"
			},
			{
				"Invoice": "273974",
				"Invoice Type": "Sales Call",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "POWERS, PHIL",
				"Invoice Date": "2019-06-10",
				"Total": "0.00"
			},
			{
				"Invoice": "249362",
				"Invoice Type": "Service Call",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "JACOBS, RHONDA",
				"Invoice Date": "2018-12-23",
				"Total": "0.00"
			},
			{
				"Invoice": "273496",
				"Invoice Type": "Service Call",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "TEXAS ROADHOUSE, JAMES BLEIL",
				"Invoice Date": "2019-06-06",
				"Total": "0.00"
			},
			{
				"Invoice": "273851",
				"Invoice Type": "Service Call",
				"Order Status": "NEW ORDER",
				"Salesperson": "Matt L",
				"Customer Name": "MELTZER, ANDREW",
				"Invoice Date": "2019-06-09",
				"Total": "0.00"
			},
			{
				"Invoice": "273923",
				"Invoice Type": "Service Call",
				"Order Status": "NEW ORDER",
				"Salesperson": "Ethan",
				"Customer Name": "SERNA, ABEL",
				"Invoice Date": "2019-06-10",
				"Total": "100.00"
			},
			{
				"Invoice": "270930",
				"Invoice Type": "Walts.com Sale In State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Richard",
				"Customer Name": "WEINBURGER, TONY",
				"Invoice Date": "2019-05-21",
				"Total": "4,053.75"
			},
			{
				"Invoice": "273138",
				"Invoice Type": "Walts.com Sale In State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Nate",
				"Customer Name": "NOBODY, JOE",
				"Invoice Date": "2019-06-04",
				"Total": "1,584.74"
			},
			{
				"Invoice": "247933",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "Control 4",
				"Salesperson": "House",
				"Customer Name": "ALCORN, DERECK",
				"Invoice Date": "2018-12-14",
				"Total": "149.97"
			},
			{
				"Invoice": "267620",
				"Invoice Type": "Walts.com Sale Out Of State",
				"Order Status": "NEW ORDER",
				"Salesperson": "Antonio",
				"Customer Name": "HAAS, ADAM",
				"Invoice Date": "2019-05-01",
				"Total": "0.00"
			}
		]';
    }

    protected function _getOpenInstallsMockJson()
    {
        return '
        [
			{
				"Invoice": "256938",
				"Invoice Type": "Installation",
				"Sales Person": "Matt L",
				"Customer Name": "TURNKEY TECHNOLGIES PHOENIX CITIZEN BANK, DAN HARTMAN",
				"Invoice Date": "2019-02-26",
				"Schedule Date": "2019-04-11",
				"Total": "1,474.40"
			},
			{
				"Invoice": "257778",
				"Invoice Type": "Installation",
				"Sales Person": "Ethan",
				"Customer Name": "KOCH, KEN",
				"Invoice Date": "2019-03-03",
				"Schedule Date": "2019-06-10",
				"Total": "21,093.53"
			},
			{
				"Invoice": "258815",
				"Invoice Type": "Installation",
				"Sales Person": "Ethan",
				"Customer Name": "SANDERS, FRANK",
				"Invoice Date": "2019-03-11",
				"Schedule Date": "2019-06-17",
				"Total": "3,387.69"
			},
			{
				"Invoice": "259331",
				"Invoice Type": "Installation",
				"Sales Person": "Ethan",
				"Customer Name": "KOCH, KEN",
				"Invoice Date": "2019-03-14",
				"Schedule Date": "2019-06-10",
				"Total": "7,031.20"
			},
			{
				"Invoice": "259381",
				"Invoice Type": "Installation",
				"Sales Person": "Ethan",
				"Customer Name": "KOCH, KEN",
				"Invoice Date": "2019-03-15",
				"Schedule Date": "2019-06-10",
				"Total": "11,695.03"
			},
			{
				"Invoice": "263843",
				"Invoice Type": "Installation",
				"Sales Person": "Matt L",
				"Customer Name": "WILLIAMS, CATHY / BRUCE",
				"Invoice Date": "2019-04-14",
				"Schedule Date": "2019-06-10",
				"Total": "32,795.50"
			},
			{
				"Invoice": "267217",
				"Invoice Type": "Installation",
				"Sales Person": "Miles",
				"Customer Name": "WESTALL, MARGARET",
				"Invoice Date": "2019-04-29",
				"Schedule Date": "2019-06-20",
				"Total": "0.00"
			},
			{
				"Invoice": "268759",
				"Invoice Type": "Installation",
				"Sales Person": "Ethan",
				"Customer Name": "KOCH, KEN",
				"Invoice Date": "2019-05-08",
				"Schedule Date": "2019-06-10",
				"Total": "7,830.59"
			},
			{
				"Invoice": "269572",
				"Invoice Type": "Installation",
				"Sales Person": "Miles",
				"Customer Name": "LARSON, CHRIS",
				"Invoice Date": "2019-05-13",
				"Schedule Date": "2019-05-13",
				"Total": "13,894.77"
			},
			{
				"Invoice": "270141",
				"Invoice Type": "Installation",
				"Sales Person": "Miles",
				"Customer Name": "NIMMONS, GERMAINE",
				"Invoice Date": "2019-05-16",
				"Schedule Date": "2019-05-16",
				"Total": "-54.00"
			},
			{
				"Invoice": "270197",
				"Invoice Type": "Installation",
				"Sales Person": "Matt L",
				"Customer Name": "BAILEY, LAURA",
				"Invoice Date": "2019-05-17",
				"Schedule Date": "2019-05-31",
				"Total": "13,891.27"
			},
			{
				"Invoice": "271019",
				"Invoice Type": "Installation",
				"Sales Person": "Bear",
				"Customer Name": "MONAHAN, TIM",
				"Invoice Date": "2019-05-22",
				"Schedule Date": "2019-05-23",
				"Total": "4,405.07"
			},
			{
				"Invoice": "271436",
				"Invoice Type": "Installation",
				"Sales Person": "Matt L",
				"Customer Name": "GOIN, PAUL",
				"Invoice Date": "2019-05-24",
				"Schedule Date": "2019-06-07",
				"Total": "0.00"
			},
			{
				"Invoice": "272010",
				"Invoice Type": "Installation",
				"Sales Person": "Ethan",
				"Customer Name": "SANDERS, FRANK",
				"Invoice Date": "2019-05-28",
				"Schedule Date": "2019-06-17",
				"Total": "1,063.18"
			},
			{
				"Invoice": "272537",
				"Invoice Type": "Installation",
				"Sales Person": "Matt L",
				"Customer Name": "MATTOX, GARY",
				"Invoice Date": "2019-05-30",
				"Schedule Date": "2019-06-11",
				"Total": "0.00"
			},
			{
				"Invoice": "272917",
				"Invoice Type": "Installation",
				"Sales Person": "Matt L",
				"Customer Name": "JAMES, MELTON",
				"Invoice Date": "2019-06-03",
				"Schedule Date": "2019-06-06",
				"Total": "625.21"
			},
			{
				"Invoice": "273401",
				"Invoice Type": "Installation",
				"Sales Person": "Ethan",
				"Customer Name": "KOCH, KEN",
				"Invoice Date": "2019-06-06",
				"Schedule Date": "2019-06-10",
				"Total": "3,042.22"
			},
			{
				"Invoice": "273411",
				"Invoice Type": "Installation",
				"Sales Person": "Bear",
				"Customer Name": "WARNER FAMILY PRACTICE, STEPHANIE",
				"Invoice Date": "2019-06-06",
				"Schedule Date": "2019-06-07",
				"Total": "0.00"
			},
			{
				"Invoice": "273494",
				"Invoice Type": "Installation",
				"Sales Person": "Matt L",
				"Customer Name": "NOTHUM, JOANN",
				"Invoice Date": "2019-06-06",
				"Schedule Date": "2019-06-06",
				"Total": "0.00"
			},
			{
				"Invoice": "273500",
				"Invoice Type": "Installation",
				"Sales Person": "Matt L",
				"Customer Name": "BHOOLA, SNEHAL",
				"Invoice Date": "2019-06-06",
				"Schedule Date": "2019-06-10",
				"Total": "0.00"
			},
			{
				"Invoice": "273549",
				"Invoice Type": "Installation",
				"Sales Person": "Ethan",
				"Customer Name": "PALANTICK, ALLAN",
				"Invoice Date": "2019-06-07",
				"Schedule Date": "2019-06-14",
				"Total": "729.69"
			},
			{
				"Invoice": "273555",
				"Invoice Type": "Installation",
				"Sales Person": "Veroncia",
				"Customer Name": "ROSALES, JESUS",
				"Invoice Date": "2019-06-07",
				"Schedule Date": "2019-06-13",
				"Total": "2,916.53"
			},
			{
				"Invoice": "273899",
				"Invoice Type": "Installation",
				"Sales Person": "Ethan",
				"Customer Name": "MATTHEW, RICHARD",
				"Invoice Date": "2019-06-10",
				"Schedule Date": "2019-06-12",
				"Total": "281.08"
			},
			{
				"Invoice": "274179",
				"Invoice Type": "Installation",
				"Sales Person": "Ethan",
				"Customer Name": "SERNA, ABEL",
				"Invoice Date": "2019-06-11",
				"Schedule Date": "2019-06-18",
				"Total": "643.94"
			}
		]';
    }

}

?>