import { Component, ElementRef, ViewChild, Input, OnChanges, SimpleChange, AfterViewInit } from '@angular/core';
import * as d3 from 'd3-selection';
import * as d3Scale from 'd3-scale';
import * as d3Shape from 'd3-shape';

import { ChartData } from '../_model/chart-data';
import { HttpService } from '../http.service';

@Component({
  selector: 'app-pie-chart',
  templateUrl: './pie-chart.component.html',
  styleUrls: ['./pie-chart.component.css']
})
export class PieChartComponent implements AfterViewInit, OnChanges {
    @Input() selectedUrl: number;
	@ViewChild('containerPieChart') element: ElementRef;
    private host: d3.selection;
    private svg: d3.selection;
    private width: number;
    private height: number;
    private radius: number;
    private htmlElement: HTMLElement;
    private statistics: ChartData[];
    statisticsTable: any[];
    loading: boolean;

    constructor(private httpService: HttpService) { }

    ngOnChanges(changes: any) {
        this.render();
    }

    ngAfterViewInit() {
        //this.render();
    }

    render() {
        this.htmlElement = this.element.nativeElement;
        this.host = d3.select(this.htmlElement);
        this.host.html('<div class="loader-big"></div>');
        this.loading = true;
        this.httpService.getLinkStatistics(this.selectedUrl).subscribe((data: ChartData[]) => {
            this.loading = false;
            this.statistics = data;
            this.setup();
            this.buildSVG();
            this.buildPie();
        });
    }

    setup() {
        this.width = 250;
        this.height = 250;
        this.radius = Math.min(this.width, this.height) / 2;
    }

    buildSVG() {
        this.host.html('');
        this.svg = this.host.append('svg')
            .attr('viewBox', '0 0 ' + this.width + ' ' + this.height)
            .append('g')
            .attr('transform', 'translate(' + (this.width / 2) + ', ' + (this.height / 2) + ')');
    }

    buildPie() {
        let pie = d3Shape.pie().sort(null).value(function(d) { return d; });
        let values = this.statistics.map(data => data.value);
        let arcSelection = this.svg.selectAll('.arc')
            .data(pie(values))
            .enter()
            .append('g')
            .attr('class', 'arc');
        this.populatePie(arcSelection);
    }

    populatePie(arcSelection: d3<d3Shape.pie.Arc>) {
        let innerRadius = (this.radius - 60);
        let outerRadius = (this.radius - 10);
        let pieColor = d3Scale.scaleOrdinal(d3Scale.schemeCategory20);
        let arc = d3Shape.arc<d3Shape.pie.Arc>().outerRadius(outerRadius).innerRadius(innerRadius);
        this.statisticsTable = [];
        arcSelection.append('path')
            .attr('d', arc)
            .attr('fill', (datum, index) => {
                let c = pieColor(this.statistics[index].label);
                this.statisticsTable.push({
                    color: c,
                    country: this.statistics[index].label,
                    hits: this.statistics[index].value
                });
                return c;
            });
        arcSelection.append('text')
            .attr('transform', (datum: any) => {
                datum.innerRadius = innerRadius;
                datum.outerRadius = outerRadius;
                return 'translate(' + arc.centroid(datum) + ')';
            })
            .text((datum, index) => this.statistics[index].value)
            .style('text-anchor', 'middle');
    }

}
